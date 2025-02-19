<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class UserController extends AbstractController
{
    #[Route('/inscription', name: 'app_inscription', methods: ['GET', 'POST'])]
    public function inscription(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            $email = $request->request->get('email');
            $password = $request->request->get('password');

            // Vérifier si l'utilisateur existe déjà
            $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

            if ($existingUser) {
                return new Response('Erreur : cet email est déjà utilisé.', 400);
            }

            // Créer un nouvel utilisateur
            $user = new User();
            $user->setNom($nom);
            $user->setPrenom($prenom);
            $user->setEmail($email);
            $user->addroles('ROLE_USER');
            $user->setPassword($password); // Pas de hachage, car pas de module de sécurité
            // Exemple de code dans le contrôleur
            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]); // Remplacer mail par email


            // Sauvegarde en base de données
            $entityManager->persist($user);
            $entityManager->flush();

            return new Response('Inscription réussie !');
        }

        // Afficher le formulaire si la requête est en GET
        return $this->render('login/inscription.html.twig');
    }
}
