<?php
namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
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
                // Stocker les informations de l'utilisateur dans la session
                $session->set('user_id', $user->getId());
                $session->set('user_name', $user->getNom());
                $session->set('user_prenom', $user->getPrenom());

                // Rediriger vers la page d'accueil de l'étudiant
                return $this->redirectToRoute('app_activity_index');
            }
        }

        return $this->render('/frontoffice/login.html.twig', [
            'error' => $error
        ]);
    }

    #[Route('/student-dashboard', name: 'app_student_dashboard')]
    public function studentDashboard(SessionInterface $session): Response
    {
        // Récupérer les informations de la session
        $userName = $session->get('user_name');
        $userPrenom = $session->get('user_prenom');

        return $this->render('user/base.html.twig', [
            'userName' => $userName,
            'userPrenom' => $userPrenom
        ]);
    }
}
