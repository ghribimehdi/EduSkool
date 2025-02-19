<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Entity\Subscription;
use App\Entity\User;
use App\Entity\Pack;
use App\Form\PaymentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/payment')]
final class PaymentController extends AbstractController
{
    #[Route(name: 'app_payment_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $payments = $entityManager
            ->getRepository(Payment::class)
            ->findAll();

        return $this->render('payment/index.html.twig', [
            'payments' => $payments,
        ]);
    }


    #[Route('/new/{packId}', name: 'app_payment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ValidatorInterface $validator, EntityManagerInterface $entityManager, $packId): Response
    {
        $payment = new Payment();
        $form = $this->createForm(PaymentType::class, $payment);
        $form->handleRequest($request);
    
    // Valider le formulaire avant de soumettre
    $errors = $validator->validate($payment);
        // Step 2: Fetch the Pack entity based on the packId
        $pack = $entityManager->getRepository(Pack::class)->find($packId);
    
        if (!$pack) {
            throw $this->createNotFoundException('Pack not found');
        }
    
        // Step 3: Handle the form submission
        if ($form->isSubmitted() && $form->isValid()) {
            // Step 3.1: Find the user (you should replace this with the actual authenticated user)
            $user = $entityManager->getRepository(User::class)->find(6); // Replace with actual authenticated user
            if (!$user) {
                throw $this->createNotFoundException('User not found');
            }
    
            // Step 3.2: Set the user for the payment
            $payment->setUser($user);
    
            // Step 3.3: Set the pack for the payment
            $payment->setPack($pack);
    
            // Step 3.4: Set the payment amount (assuming it's tied to the pack's price)
            $payment->setAmount($pack->getPrice());
    
            // Step 3.5: Set the payment date
            $payment->setPaymentDate(new \DateTime());
    
            // Persist the payment entity
            $entityManager->persist($payment);
            $entityManager->flush();
    
            // Step 4: Create or update the subscription for the user
            $subscription = new Subscription();
            $subscription->setUser($user);
            $subscription->setPack($pack);
            $subscription->setStartDate(new \DateTime());
            $subscription->setEndDate(new \DateTime('+1 month')); // Subscription lasts 1 month
            $subscription->setStatus(true); // Subscription is active
            $subscription->setPayment($payment); // Link payment to the subscription
    
            // Persist the subscription entity
            $entityManager->persist($subscription);
            $entityManager->flush(); // Now, the subscription is saved with the payment linked
    
              // ✅ Redirect to confirmation page
        return $this->redirectToRoute('app_payment_success');
        }
    
        // Render the form
        return $this->render('payment/new.html.twig', [
            'payment' => $payment,
            'form' => $form->createView(),
            'pack' => $pack,
        ]);
    }

    #[Route('/{id}', name: 'app_payment_show', methods: ['GET'])]
    public function show(Payment $payment): Response
    {
        return $this->render('payment/show.html.twig', [
            'payment' => $payment,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_payment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Payment $payment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PaymentType::class, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_payment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('payment/edit.html.twig', [
            'payment' => $payment,
            'form' => $form,
        ]);


    }
    #[Route('/payment/success', name: 'app_payment_success')]
public function success(): Response
{
    return $this->render('payment/subscription_success.html.twig');
}


    #[Route('/{id}', name: 'app_payment_delete', methods: ['POST'])]
    public function delete(Request $request, Payment $payment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$payment->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($payment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_payment_index', [], Response::HTTP_SEE_OTHER);
    }
   

}
