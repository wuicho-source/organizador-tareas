<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\TaskModel;
use App\Models\TaskImageModel;

class Home extends BaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $language = \Config\Services::language();
        $language->setLocale(session()->get('lang') ?? 'es');
    }

    public function index()
    {
        return view('welcome_message');
    }

    public function lang($locale)
    {
        session()->set('lang', $locale);
        $referer = $this->request->getServer('HTTP_REFERER');
        if (!empty($referer) && strpos($referer, base_url()) !== false) {
            return redirect()->to($referer);
        }
        return redirect()->to('/');
    }

    public function login()
    {
        if ($this->request->getMethod() === 'POST') {
            $model = new UserModel();
            $user = $model->where('email', $this->request->getPost('email'))->first();

            if ($user && password_verify($this->request->getPost('password'), $user['password'])) {
                if ($user['active'] == 0) {
                    return redirect()->back()->with('error', 'Tu cuenta no ha sido activada.');
                }
                session()->set([
                    'user_id'   => $user['id'],
                    'user_name' => $user['name'],
                    'is_admin'  => $user['is_admin'],
                    'logged_in' => true
                ]);
                return redirect()->to('/')->with('success', '¡Inicio de sesión exitoso!');
            }
            return redirect()->back()->with('error', 'Credenciales incorrectas');
        }
        return view('login');
    }

    public function signup()
    {
        if ($this->request->getMethod() === 'POST') {
            $model = new UserModel();
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $repeatPassword = $this->request->getPost('repeat_password');

            if ($password !== $repeatPassword) {
                return redirect()->back()->withInput()->with('error', 'Las contraseñas no coinciden');
            }

            if ($model->where('email', $email)->first()) {
                return redirect()->back()->withInput()->with('error', 'El correo electrónico ya está registrado');
            }

            $token = bin2hex(random_bytes(16));

            $model->save([
                'name'             => $this->request->getPost('name'),
                'email'            => $email,
                'password'         => password_hash($password, PASSWORD_BCRYPT),
                'active'           => 0,
                'is_admin'         => 0,
                'activation_token' => $token
            ]);

            $activationUrl = base_url("activate/{$token}");

            $emailService = \Config\Services::email();
            $emailService->setTo($email);
            $emailService->setSubject('Activa tu cuenta - Organizador de Tareas');
            $emailService->setMessage("Hola {$this->request->getPost('name')},<br><br>Haz clic en el siguiente enlace para activar tu cuenta:<br><a href='{$activationUrl}'>{$activationUrl}</a>");

            @$emailService->send();

            return redirect()->to('/login')->with('success', "Registro exitoso. Se ha enviado un correo de activación a {$email}.");
        }
        return view('signup');
    }

    public function activate($token)
    {
        $model = new UserModel();
        $user = $model->where('activation_token', $token)->first();

        if ($user) {
            $model->update($user['id'], [
                'active'           => 1,
                'activation_token' => null
            ]);
            return redirect()->to('/login')->with('success', '¡Cuenta activada con éxito!');
        }

        return redirect()->to('/login')->with('error', 'Enlace de activación inválido.');
    }

    public function forgotPassword()
    {
        if ($this->request->getMethod() === 'POST') {
            $email = $this->request->getPost('email');
            $model = new UserModel();
            $user = $model->where('email', $email)->first();

            if (!$user) {
                return redirect()->back()->with('error', 'No existe una cuenta registrada con ese correo.');
            }

            $token = bin2hex(random_bytes(16));
            $model->update($user['id'], [
                'reset_token'      => $token,
                'reset_expires_at' => date('Y-m-d H:i:s', strtotime('+1 hour'))
            ]);

            $resetUrl = base_url("reset-password/{$token}");

            $emailService = \Config\Services::email();
            $emailService->setTo($email);
            $emailService->setSubject('Recuperación de Contraseña');
            $emailService->setMessage("Haz clic en el siguiente enlace:<br><a href='{$resetUrl}'>{$resetUrl}</a>");

            @$emailService->send();

            return redirect()->back()->with('success', "Se ha enviado un correo con instrucciones.");
        }
        return view('forgot_password');
    }

    public function resetPasswordForm($token)
    {
        $model = new UserModel();
        $user = $model->where('reset_token', $token)->first();

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Token de recuperación inválido.');
        }

        return view('password_reset', ['token' => $token]);
    }

    public function resetPassword()
    {
        $token = $this->request->getPost('token');
        $password = $this->request->getPost('password');
        $repeatPassword = $this->request->getPost('repeat_password');

        if ($password !== $repeatPassword) {
            return redirect()->back()->with('error', 'Las contraseñas no coinciden.');
        }

        $model = new UserModel();
        $user = $model->where('reset_token', $token)->first();

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Token inválido.');
        }

        $model->update($user['id'], [
            'password'    => password_hash($password, PASSWORD_BCRYPT),
            'reset_token' => null
        ]);

        return redirect()->to('/login')->with('success', 'Contraseña restablecida con éxito.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/')->with('success', 'Sesión finalizada exitosamente.');
    }

    public function tasks()
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');
        
        $model = new TaskModel();
        $search = $this->request->getGet('search') ?? '';
        $showHidden = $this->request->getGet('show_hidden') ?? '0';

        $builder = $model->builder();
        $builder->where('user_id', session()->get('user_id'))
                ->where('is_hidden', $showHidden);

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('class_name', $search)
                    ->orLike('notes', $search)
                    ->groupEnd();
        }

        $data['tasks'] = $builder->get()->getResultArray();
        $data['search'] = $search;
        $data['show_hidden'] = $showHidden;

        return view('tasks/index', $data);
    }

    public function showTask($id)
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');
        
        $taskModel = new TaskModel();
        $imageModel = new TaskImageModel();

        $task = $taskModel->where('id', $id)->where('user_id', session()->get('user_id'))->first();

        if (!$task) {
            return redirect()->to('/tasks')->with('error', 'No tienes permiso para ver esta tarea.');
        }

        $data['task'] = $task;
        $data['images'] = $imageModel->where('task_id', $id)->findAll();

        return view('tasks/show', $data);
    }

    public function uploadTaskImages($taskId)
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');
        
        $taskModel = new TaskModel();
        $task = $taskModel->where('id', $taskId)->where('user_id', session()->get('user_id'))->first();

        if (!$task) {
            return redirect()->to('/tasks')->with('error', 'No tienes permiso para modificar esta tarea.');
        }

        $imageModel = new TaskImageModel();
        $files = $this->request->getFileMultiple('images');

        if ($files) {
            foreach ($files as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move('uploads/tasks', $newName);

                    $imageModel->save([
                        'task_id'    => $taskId,
                        'image_path' => $newName
                    ]);
                }
            }
        }

        return redirect()->to("/tasks/show/{$taskId}")->with('success', 'Imágenes subidas con éxito.');
    }

    public function deleteTaskImage($imageId, $taskId)
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');
        
        $taskModel = new TaskModel();
        $task = $taskModel->where('id', $taskId)->where('user_id', session()->get('user_id'))->first();

        if (!$task) {
            return redirect()->to('/tasks')->with('error', 'No tienes permiso para modificar esta tarea.');
        }

        $imageModel = new TaskImageModel();
        $img = $imageModel->find($imageId);

        if ($img) {
            if (file_exists('uploads/tasks/' . $img['image_path'])) {
                unlink('uploads/tasks/' . $img['image_path']);
            }
            $imageModel->delete($imageId);
        }

        return redirect()->to("/tasks/show/{$taskId}")->with('success', 'Imagen eliminada.');
    }

    public function newTask()
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');
        return view('tasks/form');
    }

    public function createTask()
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');
        $model = new TaskModel();

        $model->save([
            'user_id'       => session()->get('user_id'),
            'class_name'    => $this->request->getPost('class_name'),
            'assigned_date' => $this->request->getPost('assigned_date'),
            'due_date'      => $this->request->getPost('due_date'),
            'notes'         => $this->request->getPost('notes'),
            'is_hidden'     => 0
        ]);

        $taskId = $model->getInsertID();

        $file = $this->request->getFile('image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $imageName = $file->getRandomName();
            $file->move('uploads/tasks', $imageName);

            $imageModel = new TaskImageModel();
            $imageModel->save([
                'task_id'    => $taskId,
                'image_path' => $imageName
            ]);
        }

        return redirect()->to('/tasks')->with('success', 'Tarea creada con éxito');
    }

    public function editTask($id)
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');
        $model = new TaskModel();
        
        $task = $model->where('id', $id)->where('user_id', session()->get('user_id'))->first();

        if (!$task) {
            return redirect()->to('/tasks')->with('error', 'No tienes permiso para editar esta tarea.');
        }

        $data['task'] = $task;
        return view('tasks/form', $data);
    }

    public function updateTask($id)
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');
        $model = new TaskModel();

        $task = $model->where('id', $id)->where('user_id', session()->get('user_id'))->first();

        if (!$task) {
            return redirect()->to('/tasks')->with('error', 'No tienes permiso para actualizar esta tarea.');
        }

        $model->update($id, [
            'class_name'    => $this->request->getPost('class_name'),
            'assigned_date' => $this->request->getPost('assigned_date'),
            'due_date'      => $this->request->getPost('due_date'),
            'notes'         => $this->request->getPost('notes')
        ]);

        return redirect()->to('/tasks')->with('success', 'Tarea actualizada');
    }

    public function toggleHideTask($id)
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');
        $model = new TaskModel();

        $task = $model->where('id', $id)->where('user_id', session()->get('user_id'))->first();

        if (!$task) {
            return redirect()->to('/tasks')->with('error', 'No tienes permiso para modificar esta tarea.');
        }

        $model->update($id, [
            'is_hidden' => $task['is_hidden'] ? 0 : 1
        ]);

        return redirect()->to('/tasks')->with('success', 'Estado de la tarea actualizado');
    }

    public function deleteTask($id)
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');
        $model = new TaskModel();

        $task = $model->where('id', $id)->where('user_id', session()->get('user_id'))->first();

        if (!$task) {
            return redirect()->to('/tasks')->with('error', 'No tienes permiso para eliminar esta tarea.');
        }

        $imageModel = new TaskImageModel();
        $images = $imageModel->where('task_id', $id)->findAll();
        foreach ($images as $img) {
            if (file_exists('uploads/tasks/' . $img['image_path'])) {
                unlink('uploads/tasks/' . $img['image_path']);
            }
        }
        $imageModel->where('task_id', $id)->delete();

        $model->delete($id);
        return redirect()->to('/tasks')->with('success', 'Tarea eliminada');
    }
}
