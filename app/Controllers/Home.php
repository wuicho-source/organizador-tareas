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

    public function index(): string
    {
        return view('welcome_message');
    }

    public function lang($locale)
    {
        session()->set('lang', $locale);
        return redirect()->back();
    }

    public function login()
    {
        if (session()->get('logged_in')) return redirect()->to('/tasks');

        if ($this->request->getMethod() === 'POST') {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            $userModel = new UserModel();
            $user = $userModel->where('email', $email)->first();

            if ($user && password_verify($password, $user['password'])) {
                session()->set([
                    'user_id'   => $user['id'],
                    'user_name' => $user['name'],
                    'is_admin'  => $user['is_admin'],
                    'logged_in' => true,
                ]);
                return redirect()->to('/tasks');
            }

            return redirect()->back()->with('error', 'Credenciales incorrectas');
        }

        return view('login');
    }

    public function signup()
    {
        if (session()->get('logged_in')) return redirect()->to('/tasks');

        if ($this->request->getMethod() === 'POST') {
            $userModel = new UserModel();
            $userModel->save([
                'name'     => $this->request->getPost('name'),
                'email'    => $this->request->getPost('email'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
                'active'   => 1,
                'is_admin' => 0
            ]);

            return redirect()->to('/login')->with('success', 'Registro exitoso. Ya puedes iniciar sesión.');
        }

        return view('signup');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }

    public function tasks()
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');

        $taskModel = new TaskModel();
        $userId = session()->get('user_id');
        $search = $this->request->getGet('search');

        if (!empty($search)) {
            $taskModel->like('class_name', $search);
        }

        $data['tasks'] = $taskModel->where('user_id', $userId)->orderBy('id', 'DESC')->paginate(5);
        $data['pager'] = $taskModel->pager;
        $data['search'] = $search;

        return view('tasks/index', $data);
    }

    public function newTask()
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');
        return view('tasks/form');
    }

    public function createTask()
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');

        $taskModel = new TaskModel();
        $today = date('Y-m-d');

        $taskModel->save([
            'user_id'       => session()->get('user_id'),
            'class_name'    => $this->request->getPost('class_name'),
            'assigned_date' => $today,
            'due_date'      => $today,
            'notes'         => ''
        ]);

        return redirect()->to('/tasks')->with('success', 'Tarea creada con éxito');
    }

    public function editTask($id)
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');

        $taskModel = new TaskModel();
        $data['task'] = $taskModel->where('id', $id)->where('user_id', session()->get('user_id'))->first();

        if (!$data['task']) return redirect()->to('/tasks');

        return view('tasks/form', $data);
    }

    public function updateTask($id)
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');

        $taskModel = new TaskModel();
        $task = $taskModel->where('id', $id)->where('user_id', session()->get('user_id'))->first();

        if ($task) {
            $taskModel->update($id, [
                'class_name' => $this->request->getPost('class_name'),
            ]);
        }

        return redirect()->to('/tasks')->with('success', 'Tarea actualizada con éxito');
    }

    public function showTask($id)
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');

        $taskModel = new TaskModel();
        $imageModel = new TaskImageModel();

        $data['task'] = $taskModel->where('id', $id)->where('user_id', session()->get('user_id'))->first();
        if (!$data['task']) return redirect()->to('/tasks');

        $data['images'] = $imageModel->where('task_id', $id)->findAll();

        return view('tasks/show', $data);
    }

    public function uploadTaskImages($id)
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');

        $taskModel = new TaskModel();
        $task = $taskModel->where('id', $id)->where('user_id', session()->get('user_id'))->first();

        if (!$task) return redirect()->to('/tasks');

        $imageModel = new TaskImageModel();
        $files = $this->request->getFiles();

        if (isset($files['images'])) {
            foreach ($files['images'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move('uploads/tasks', $newName);

                    $imageModel->save([
                        'task_id'    => $id,
                        'image_path' => $newName
                    ]);
                }
            }
        }

        return redirect()->to('/tasks/show/' . $id)->with('success', 'Imágenes subidas con éxito');
    }

    public function deleteTaskImage($taskId, $imageId)
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');

        $imageModel = new TaskImageModel();
        $img = $imageModel->find($imageId);

        if ($img) {
            if (file_exists('uploads/tasks/' . $img['image_path'])) {
                unlink('uploads/tasks/' . $img['image_path']);
            }
            $imageModel->delete($imageId);
        }

        return redirect()->to('/tasks/show/' . $taskId)->with('success', 'Imagen eliminada');
    }

    public function deleteTask($id)
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');

        $taskModel = new TaskModel();
        $imageModel = new TaskImageModel();

        $task = $taskModel->where('id', $id)->where('user_id', session()->get('user_id'))->first();

        if ($task) {
            $images = $imageModel->where('task_id', $id)->findAll();
            foreach ($images as $img) {
                if (file_exists('uploads/tasks/' . $img['image_path'])) {
                    unlink('uploads/tasks/' . $img['image_path']);
                }
            }
            $imageModel->where('task_id', $id)->delete();
            $taskModel->delete($id);
        }

        return redirect()->to('/tasks')->with('success', 'Tarea eliminada con éxito');
    }
}
