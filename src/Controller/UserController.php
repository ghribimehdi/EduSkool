<?php
namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Créer des utilisateurs avec différents rôles
        $user1 = new User();
        $user1->setEmail('enseignant1@example.com');
        $user1->setPassword(password_hash('password1', PASSWORD_BCRYPT));  // Assurez-vous d'utiliser un mot de passe haché
        $user1->setRoles(['ROLE_ENSEIGNANT']);
        $user1->setLastname('Doe');
        $user1->setFirstname('John');
        $user1->setAddress('123 Street');
        $user1->setZipcode('12345');
        $user1->setCity('Some City');
        // Remplacez DateTime par DateTimeImmutable
$createdAt = new \DateTimeImmutable();
$user1->setCreatedAt($createdAt);

        
        // Ajouter l'utilisateur à la base de données
        $entityManager->persist($user1);
        
        // Créer un autre utilisateur avec un rôle de participant
        $user2 = new User();
        $user2->setEmail('participant1@example.com');
        $user2->setPassword(password_hash('password2', PASSWORD_BCRYPT));  // Assurez-vous d'utiliser un mot de passe haché
        $user2->setRoles(['ROLE_PARTICIPANT']);
        $user2->setLastname('Smith');
        $user2->setFirstname('Jane');
        $user2->setAddress('456 Avenue');
        $user2->setZipcode('67890');
        $user2->setCity('Other City');
        // Remplacez DateTime par DateTimeImmutable
$createdAt = new \DateTimeImmutable();
$user2->setCreatedAt($createdAt);

        

        // Ajouter l'utilisateur à la base de données
        $entityManager->persist($user2);

        // Sauvegarder dans la base de données
        $entityManager->flush();

        // Retourner une réponse
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'message' => 'Les utilisateurs ont été créés avec succès.',
        ]);
    }
}
