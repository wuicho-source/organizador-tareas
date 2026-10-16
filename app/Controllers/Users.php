<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\TaskModel;
use App\Models\TaskImageModel;

class Users extends BaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $language = \Config\Services::language();
        $language->setLocale(session()->get('lang') ?? 'es');
    }

    public function index()
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');
        if (!session()->get('is_admin')) return redirect()->to('/tasks')->with('error', 'Acceso denegado.');

        $model = new UserModel();
        $order = $this->request->getGet('order') ?? 'newer';
        $sortDirection = ($order === 'older') ? 'ASC' : 'DESC';

        $data['users'] = $model->orderBy('id', $sortDirection)->findAll();
        $data['order'] = $order;

        return view('users/index', $data);
    }

    public function showUser($id)
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');
        if (!session()->get('is_admin')) return redirect()->to('/tasks')->with('error', 'Acceso denegado.');

        $model = new UserModel();
        $data['user'] = $model->find($id);

        if (!$data['user']) {
            return redirect()->to('/users')->with('error', 'Usuario no encontrado.');
        }

        return view('users/show', $data);
    }

    public function createUser()
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');
        if (!session()->get('is_admin')) return redirect()->to('/tasks')->with('error', 'Acceso denegado.');

        if ($this->request->getMethod() === 'POST') {
            $password = $this->request->getPost('password');
            $repeatPassword = $this->request->getPost('repeat_password');

            if ($password !== $repeatPassword) {
                return redirect()->back()->withInput()->with('error', 'Las contraseñas no coinciden.');
            }

            $model = new UserModel();

            $model->save([
                'name'     => $this->request->getPost('name'),
                'email'    => $this->request->getPost('email'),
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'active'   => $this->request->getPost('active') ? 1 : 0,
                'is_admin' => $this->request->getPost('is_admin') ? 1 : 0
            ]);

            return redirect()->to('/users')->with('success', 'Usuario creado exitosamente.');
        }

        return view('users/form');
    }

    public function editUser($id)
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');
        if (!session()->get('is_admin')) return redirect()->to('/tasks')->with('error', 'Acceso denegado.');

        $model = new UserModel();
        $data['user'] = $model->find($id);

        if (!$data['user']) {
            return redirect()->to('/users')->with('error', 'Usuario no encontrado.');
        }

        return view('users/form', $data);
    }

    public function updateUser($id)
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');
        if (!session()->get('is_admin')) return redirect()->to('/tasks')->with('error', 'Acceso denegado.');

        $password = $this->request->getPost('password');
        $repeatPassword = $this->request->getPost('repeat_password');

        if (!empty($password) && $password !== $repeatPassword) {
            return redirect()->back()->withInput()->with('error', 'Las contraseñas no coinciden.');
        }

        $model = new UserModel();
        $updateData = [
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'active'   => $this->request->getPost('active') ? 1 : 0,
            'is_admin' => $this->request->getPost('is_admin') ? 1 : 0
        ];

        if (!empty($password)) {
            $updateData['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $model->update($id, $updateData);

        return redirect()->to('/users/show/' . $id)->with('success', 'Usuario actualizado con éxito.');
    }

    public function delete($id)
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');
        if (!session()->get('is_admin')) return redirect()->to('/tasks')->with('error', 'Acceso denegado.');

        if ($id == session()->get('user_id')) {
            return redirect()->to('/users')->with('error', 'No puedes eliminarte a ti mismo.');
        }

        $userModel = new UserModel();
        $taskModel = new TaskModel();
        $imageModel = new TaskImageModel();

        $user = $userModel->find($id);

        if ($user) {
            if (!empty($user['profile_image']) && file_exists('uploads/profiles/' . $user['profile_image'])) {
                unlink('uploads/profiles/' . $user['profile_image']);
            }

            $tasks = $taskModel->where('user_id', $id)->findAll();
            foreach ($tasks as $task) {
                $images = $imageModel->where('task_id', $task['id'])->findAll();
                foreach ($images as $img) {
                    if (file_exists('uploads/tasks/' . $img['image_path'])) {
                        unlink('uploads/tasks/' . $img['image_path']);
                    }
                }
                $imageModel->where('task_id', $task['id'])->delete();
            }

            $taskModel->where('user_id', $id)->delete();
            $userModel->delete($id);

            return redirect()->to('/users')->with('success', 'Usuario y sus datos eliminados.');
        }

        return redirect()->to('/users')->with('error', 'Usuario no encontrado.');
    }
}
