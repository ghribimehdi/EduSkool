<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ActivityRepository;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Activity; 
use Doctrine\ORM\EntityManagerInterface;


final class BackendActivityController extends AbstractController
{
    #[Route('/backend/activity', name: 'app_backend_activity')]
    public function index(ActivityRepository $activityRepository): Response
    {
        // Récupérer toutes les activités
        $activities = $activityRepository->findAll();

        return $this->render('backend_activity/index.html.twig', [
            'activities' => $activities,
        ]);
    }

    #[Route('/backend/activity/approve/{id}', name: 'app_backend_activity_approve')]
public function approve(Activity $activity, EntityManagerInterface $entityManager): Response
{
    $activity->setApproved(true);
    $entityManager->flush();

    $this->addFlash('success', 'L\'activité a été approuvée avec succès.');

    return $this->redirectToRoute('app_backend_activity');
}


#[Route('/backend/dashboard', name: 'backend_dashboard')]
public function adminDashboard(): Response
{
    return $this->render('backend_activity/dashboard.html.twig');
}




}
