<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\CoursRepository;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Cours;
use Doctrine\ORM\EntityManagerInterface;


final class BackendCoursController extends AbstractController
{
    #[Route('/backend/cours', name: 'app_backend_cours')]
    public function index(ActivityRepository $activityRepository): Response
    {
        // Récupérer toutes les activités
        $activities = $activityRepository->findAll();

        return $this->render('backendcours/index.html.twig', [
            'activities' => $activities,
        ]);
    }

    #[Route('/backend/cours/approve/{id}', name: 'app_backend_cours_approve')]
public function approve(Activity $activity, EntityManagerInterface $entityManager): Response
{
    $activity->setApproved(true);
    $entityManager->flush();

    $this->addFlash('success', 'L\'activité a été approuvée avec succès.');

    return $this->redirectToRoute('app_backend_cours');
}


#[Route('/backend/dashboard', name: 'backend_dashboard')]
public function adminDashboard(): Response
{
    return $this->render('backendcours/dashboard.html.twig');
}




}
