<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\TaskModel;

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

        helper('cookie');

        if ($this->request->getMethod() === 'POST') {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $remember = $this->request->getPost('remember_me');

            $userModel = new UserModel();
            $user = $userModel->where('email', $email)->first();

            if ($user && password_verify($password, $user['password'])) {
                if ($remember) {
                    set_cookie('remember_email', $email, 30 * 86400);
                    set_cookie('remember_pass', $password, 30 * 86400);
                } else {
                    delete_cookie('remember_email');
                    delete_cookie('remember_pass');
                }

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

        $data['remembered_email'] = get_cookie('remember_email');
        $data['remembered_password'] = get_cookie('remember_pass');

        return view('login', $data);
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

    public function forgotPassword()
    {
        if ($this->request->getMethod() === 'POST') {
            $email = $this->request->getPost('email');
            $userModel = new UserModel();
            $user = $userModel->where('email', $email)->first();

            if ($user) {
                $token = bin2hex(random_bytes(16));
                session()->set('reset_token_' . $token, $user['id']);

                $resetUrl = base_url('reset-password/' . $token);

                $emailService = \Config\Services::email();
                $emailService->setTo($email);
                $emailService->setSubject('Restablecer Contraseña - Organizador de Tareas');
                $emailService->setMailType('html');

                $message = "
                    <div style='font-family: Arial, sans-serif; padding: 20px; color: #333;'>
                        <h2>Restablecer tu contraseña</h2>
                        <p>Hemos recibido una solicitud para cambiar tu contraseña. Haz clic en el siguiente botón para continuar:</p>
                        <p style='margin: 30px 0;'>
                            <a href='{$resetUrl}' style='background-color: #0d6efd; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block;'>Cambiar Contraseña</a>
                        </p>
                        <p style='font-size: 12px; color: #777;'>Si no solicitaste este cambio, puedes ignorar este correo.</p>
                    </div>
                ";

                $emailService->setMessage($message);
                $emailService->send();
            }

            return redirect()->to('/login')->with('success', 'Se han enviado las instrucciones a tu correo electrónico.');
        }

        return view('forgot_password');
    }

    public function resetPasswordForm($token)
    {
        if (!session()->has('reset_token_' . $token)) {
            return redirect()->to('/login')->with('error', 'El enlace de recuperación es inválido o ha expirado.');
        }

        return view('reset_password', ['token' => $token]);
    }

    public function resetPassword()
    {
        $token = $this->request->getPost('token');
        $userId = session()->get('reset_token_' . $token);

        if (!$userId) {
            return redirect()->to('/login')->with('error', 'El enlace de recuperación ha expirado.');
        }

        $password = $this->request->getPost('password');
        $repeatPassword = $this->request->getPost('repeat_password');

        if ($password !== $repeatPassword) {
            return redirect()->back()->with('error', 'Las contraseñas no coinciden.');
        }

        $userModel = new UserModel();
        $userModel->update($userId, [
            'password' => password_hash($password, PASSWORD_BCRYPT)
        ]);

        session()->remove('reset_token_' . $token);

        return redirect()->to('/login')->with('success', 'Tu contraseña ha sido actualizada con éxito.');
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
        $order = $this->request->getGet('order') ?? 'newer';

        $sortDirection = ($order === 'older') ? 'ASC' : 'DESC';

        if (!empty($search)) {
            $taskModel->like('class_name', $search);
        }

        $data['tasks'] = $taskModel->where('user_id', $userId)->orderBy('id', $sortDirection)->findAll();
        $data['search'] = $search;
        $data['order'] = $order;

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

    public function deleteTask($id)
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');

        $taskModel = new TaskModel();
        $taskModel->where('id', $id)->where('user_id', session()->get('user_id'))->delete();

        return redirect()->to('/tasks')->with('success', 'Tarea eliminada con éxito');
    }
}
