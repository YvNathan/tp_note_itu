<?php
namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    public function login()
    {
        // Si déjà connecté → rediriger
        if (session()->get('user_id')) {
            return redirect()->to(site_url('etudiants'));
        }
        return view('template/login');
    }

    public function doLogin()
    {
        $userModel    = new UserModel();
        $email        = $this->request->getPost('email');
        $mot_de_passe = $this->request->getPost('mot_de_passe');

        $user = $userModel->getUserByEmail($email);

        if (!$user) {
            return redirect()->back()->with('error', 'Identifiants invalides');
        }

        $stored  = $user['mot_de_passe'];
        $isValid = false;

        // Vérifie si le mot de passe est hashé
        if (is_string($stored) && (
            str_starts_with($stored, '$2y$') ||
            str_starts_with($stored, '$2b$') ||
            str_starts_with($stored, '$argon')
        )) {
            if (password_verify($mot_de_passe, $stored)) {
                $isValid = true;
            }
        } else {
            // Mot de passe en clair → on vérifie puis on migre vers un hash
            if ($mot_de_passe === $stored) {
                $isValid  = true;
                $newHash  = password_hash($mot_de_passe, PASSWORD_DEFAULT);
                try {
                    $userModel->update($user['id'], ['mot_de_passe' => $newHash]);
                } catch (\Exception $e) {
                    // silencieux
                }
            }
        }

        if (!$isValid) {
            return redirect()->back()->with('error', 'Identifiants invalides');
        }

        // Stocker en session
        session()->set([
            'user_id'    => $user['id'],
            'user_nom'   => $user['nom'],
            'user_email' => $user['email'],
            'user_role'  => $user['role'],
        ]);

        return redirect()->to(site_url('etudiants'))->with('success', 'Connexion réussie');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('login'));
    }
}