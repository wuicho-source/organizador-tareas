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

        $data['users'] = $model->orderBy('id', 'ASC')->paginate(10);
        $data['pager'] = $model->pager;

        return view('users/index', $data);
    }

    public function createUser()
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');
        if (!session()->get('is_admin')) return redirect()->to('/tasks')->with('error', 'Acceso denegado.');

        if ($this->request->getMethod() === 'POST') {
            $model = new UserModel();

            $model->save([
                'name'     => $this->request->getPost('name'),
                'email'    => $this->request->getPost('email'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
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

        $model = new UserModel();
        $updateData = [
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'active'   => $this->request->getPost('active') ? 1 : 0,
            'is_admin' => $this->request->getPost('is_admin') ? 1 : 0
        ];

        if (!empty($this->request->getPost('password'))) {
            $updateData['password'] = password_hash($this->request->getPost('password'), PASSWORD_BCRYPT);
        }

        $model->update($id, $updateData);

        return redirect()->to('/users')->with('success', 'Usuario actualizado con éxito.');
    }
}
