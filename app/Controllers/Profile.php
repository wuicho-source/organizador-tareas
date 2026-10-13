<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\TaskModel;
use App\Models\TaskImageModel;

class Profile extends BaseController
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
        $model = new UserModel();
        $data['user'] = $model->find(session()->get('user_id'));
        return view('profile/index', $data);
    }

    public function confirmPassword()
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');
        if ($this->request->getMethod() === 'POST') {
            $model = new UserModel();
            $user = $model->find(session()->get('user_id'));
            if (password_verify($this->request->getPost('password'), $user['password'])) {
                session()->set('profile_edit_authorized', true);
                return redirect()->to('/profile/edit');
            }
            return redirect()->back()->with('error', 'Contraseña incorrecta');
        }
        return view('profile/confirm_password');
    }

    public function edit()
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');
        if (!session()->get('profile_edit_authorized')) return redirect()->to('/profile/confirm-password');

        $model = new UserModel();
        if ($this->request->getMethod() === 'POST') {
            $name = $this->request->getPost('name');
            $email = $this->request->getPost('email');
            $bio = $this->request->getPost('bio');

            $model->update(session()->get('user_id'), [
                'name'  => $name,
                'email' => $email,
                'bio'   => $bio
            ]);

            session()->set('user_name', $name);
            session()->remove('profile_edit_authorized');
            return redirect()->to('/profile')->with('success', 'Perfil actualizado con éxito');
        }

        $data['user'] = $model->find(session()->get('user_id'));
        return view('profile/edit', $data);
    }

    public function changePassword()
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');

        if ($this->request->getMethod() === 'POST') {
            $currentPassword = $this->request->getPost('current_password');
            $newPassword = $this->request->getPost('new_password');
            $repeatNewPassword = $this->request->getPost('repeat_new_password');

            $model = new UserModel();
            $user = $model->find(session()->get('user_id'));

            if (!password_verify($currentPassword, $user['password'])) {
                return redirect()->back()->with('error', 'La contraseña actual es incorrecta');
            }

            if ($newPassword !== $repeatNewPassword) {
                return redirect()->back()->with('error', 'Las nuevas contraseñas no coinciden');
            }

            $model->update(session()->get('user_id'), [
                'password' => password_hash($newPassword, PASSWORD_BCRYPT)
            ]);

            return redirect()->to('/profile')->with('success', 'Contraseña cambiada con éxito');
        }

        return view('profile/change_password');
    }

    public function changeImage()
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');

        if ($this->request->getMethod() === 'POST') {
            $file = $this->request->getFile('profile_image');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move('uploads/profiles', $newName);

                $model = new UserModel();
                $user = $model->find(session()->get('user_id'));

                if (!empty($user['profile_image']) && file_exists('uploads/profiles/' . $user['profile_image'])) {
                    unlink('uploads/profiles/' . $user['profile_image']);
                }

                $model->update(session()->get('user_id'), [
                    'profile_image' => $newName
                ]);

                return redirect()->to('/profile')->with('success', 'Imagen de perfil actualizada');
            }
            return redirect()->back()->with('error', 'Error al subir la imagen');
        }

        return view('profile/change_image');
    }

    public function deleteImage()
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');

        if ($this->request->getMethod() === 'POST') {
            $model = new UserModel();
            $user = $model->find(session()->get('user_id'));

            if (!empty($user['profile_image']) && file_exists('uploads/profiles/' . $user['profile_image'])) {
                unlink('uploads/profiles/' . $user['profile_image']);
            }

            $model->update(session()->get('user_id'), [
                'profile_image' => null
            ]);

            return redirect()->to('/profile')->with('success', 'Imagen de perfil eliminada');
        }

        return view('profile/delete_image');
    }

    public function deleteAccount()
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');

        if ($this->request->getMethod() === 'POST') {
            $userId = session()->get('user_id');
            $userModel = new UserModel();
            $taskModel = new TaskModel();
            $imageModel = new TaskImageModel();

            $user = $userModel->find($userId);

            if (!empty($user['profile_image']) && file_exists('uploads/profiles/' . $user['profile_image'])) {
                unlink('uploads/profiles/' . $user['profile_image']);
            }

            $tasks = $taskModel->where('user_id', $userId)->findAll();
            foreach ($tasks as $task) {
                $images = $imageModel->where('task_id', $task['id'])->findAll();
                foreach ($images as $img) {
                    if (file_exists('uploads/tasks/' . $img['image_path'])) {
                        unlink('uploads/tasks/' . $img['image_path']);
                    }
                }
                $imageModel->where('task_id', $task['id'])->delete();
            }

            $taskModel->where('user_id', $userId)->delete();
            $userModel->delete($userId);

            session()->destroy();
            return redirect()->to('/')->with('success', 'Tu cuenta ha sido eliminada correctamente.');
        }

        return view('profile/delete_account');
    }
}
