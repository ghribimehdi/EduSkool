<?php

namespace App\Controller;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use App\Entity\Activity;
use App\Form\ActivityType;
use App\Repository\ActivityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/activity')]
final class ActivityController extends AbstractController{
    #[Route(name: 'app_activity_index', methods: ['GET', 'POST'])]
    public function index(ActivityRepository $activityRepository, CommentaireRepository $commentaireRepository, Request $request): Response
{
    $activities = $activityRepository->findApprovedActivities();
    $averageNotes = $commentaireRepository->findAverageNoteByActivity();


     // Création du formulaire de notation
     $form = $this->createForm(CommentaireType::class); // Remplacez "NoteType" par votre type de formulaire réel
     $form->handleRequest($request); // Gestion des données soumise
     

    return $this->render('activity/index.html.twig', [
        'activities' => $activities,
        'averageNotes' => $averageNotes, // Passer les moyennes des commentaires à la vue

        'form' => $form->createView(), // Passer le formulaire à la vue

    ]);
}



#[Route('/new', name: 'app_activity_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $activity = new Activity();
    $form = $this->createForm(ActivityType::class, $activity);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Gestion de l'image téléchargée
        $imageFile = $form->get('imageFileName')->getData(); // Récupérer le fichier

        if ($imageFile) {
            $newFilename = uniqid().'.'.$imageFile->guessExtension();
            $imageFile->move(
                $this->getParameter('upload_dir'),
                $newFilename
            );
            $activity->setImageFileName($newFilename);
        }
        
        // Sauvegarder l'activité
        $entityManager->persist($activity);
        $entityManager->flush();

        return $this->redirectToRoute('app_activity_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('activity/new.html.twig', [
        'activity' => $activity,
        'form' => $form,
    ]);
}


    #[Route('/{id}', name: 'app_activity_show', methods: ['GET'])]
    public function show(ActivityRepository $activityRepository): Response
    {        $activities = $activityRepository->findAll();

        return $this->render('activity/show.html.twig', [
            'activities' => $activities,
        ]);

    }

    #[Route('/{id}/edit', name: 'app_activity_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Activity $activity, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ActivityType::class, $activity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_activity_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('activity/edit.html.twig', [
            'activity' => $activity,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_activity_delete', methods: ['POST'])]
    public function delete(Request $request, Activity $activity, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$activity->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($activity);
            $entityManager->flush();
        }

        return $this->redirect($request->headers->get('referer'));

    }



    #[Route('/activity/{id}', name: 'app_activity_details')]
public function details(
    ActivityRepository $activityRepository, 
    CommentaireRepository $commentaireRepository, 
    Request $request, 
    EntityManagerInterface $entityManager, 
    int $id
): Response {
    $activity = $activityRepository->find($id);

    if (!$activity) {
        throw $this->createNotFoundException('Activité non trouvée.');
    }

    // Liste des commentaires de cette activité
    $commentaires = $commentaireRepository->findBy(['activity' => $activity]);

    // Création du formulaire de commentaire
    $commentaire = new Commentaire();
    $form = $this->createForm(CommentaireType::class, $commentaire);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $commentaire->setActivity($activity);
        $entityManager->persist($commentaire);
        $entityManager->flush();

        $this->addFlash('success', 'Votre commentaire a été ajouté avec succès.');

        return $this->redirectToRoute('app_activity_details', ['id' => $id]);
    }

    return $this->render('activity/details.html.twig', [
        'activity' => $activity,
        'commentaires' => $commentaires,
        'form' => $form->createView(),
    ]);
}
    

}
