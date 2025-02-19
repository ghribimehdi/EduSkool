<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\SubscriptionRepository;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Subscription; 
use Doctrine\ORM\EntityManagerInterface;


final class BackendSubscriptionController extends AbstractController
{
    #[Route('/backend/subscription', name: 'app_backend_subscription')]
    public function index(SubscriptionRepository $subscriptionRepository): Response
    {
        // Récupérer toutes les activités
        $subscriptions = $subscriptionRepository->findAll();

        return $this->render('backend_subscription/index.html.twig', [
            'subscriptions' => $subscriptions,
        ]);
    }


#[Route('/backend/dashboard', name: 'backend_dashboard')]
public function adminDashboard(): Response
{
    return $this->render('backend_subscription/dashboard.html.twig');
}




}
