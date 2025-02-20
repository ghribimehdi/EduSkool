<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\PaymentRepository;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Subscription; 
use Doctrine\ORM\EntityManagerInterface;


final class BackendPaymentController extends AbstractController
{
    #[Route('/backend/payment', name: 'app_backend_Payment')]
    public function index(PaymentRepository $paymentRepository): Response
    {
        // Récupérer toutes les activités
        $payments = $paymentRepository->findAll();

        return $this->render('backend_payment/index.html.twig', [
            'payments' => $payments,
        ]);
    }


#[Route('/backend/dashboard', name: 'backend_dashboard')]
public function adminDashboard(): Response
{
    return $this->render('backend_subscription/dashboard.html.twig');
}




}