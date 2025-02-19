<?php
namespace App\Controller;

use App\Entity\Admin;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginAdminController extends AbstractController
{
    #[Route('/admin/login', name: 'app_admin_login', methods: ['GET', 'POST'])]
    public function login(Request $request, EntityManagerInterface $entityManager): Response
    {
        $error = null;

        // Define the predefined admin credentials
        $predefinedEmail = 'admin@example.com'; // Replace with the actual email
        $predefinedPassword = 'admin123';  // Replace with the actual password

        // Set default values for the login form
        $email = $predefinedEmail;
        $password = $predefinedPassword;

        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $password = $request->request->get('password');

            // Vérifier si l'admin existe
            $admin = $entityManager->getRepository(Admin::class)->findOneBy(['email' => $email]);

            if (!$admin) {
                $error = "Administrateur introuvable.";
            } elseif ($admin->getPassword() !== $password) {
                $error = "Mot de passe incorrect.";
            } else {
                // Rediriger vers la page admin après connexion réussie
                return $this->redirectToRoute('app_base_admin');
            }
        }

        return $this->render('admin/LoginAdmin.html.twig', [
            'error' => $error,
            'predefinedEmail' => $predefinedEmail,
            'predefinedPassword' => $predefinedPassword
        ]);
    }

    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    public function adminDashboard(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    // New route for the base admin page
    #[Route('/admin/base', name: 'app_base_admin')]
    public function baseAdmin(): Response
    {
        return $this->render('admin/baseAdmin.html.twig');
    }
}
