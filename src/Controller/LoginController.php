<?php
namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(Request $request, EntityManagerInterface $entityManager): Response
    {
        $error = null;

        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $password = $request->request->get('password');

            // Vérifier si l'utilisateur existe
            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

            if (!$user) {
                $error = "Utilisateur introuvable.";
            } elseif ($user->getPassword() !== $password) {
                $error = "Mot de passe incorrect.";
            } else {
                // Rediriger vers la page d'accueil de l'étudiant
                return $this->redirectToRoute('app_student_dashboard');
            }
        }

        return $this->render('login/login.html.twig', [
            'error' => $error
        ]);
    }

    #[Route('/student-dashboard', name: 'app_student_dashboard')]
    public function studentDashboard(): Response
    {
        return $this->render('frontoffice/index.html.twig');
    }
}
