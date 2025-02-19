<?php

namespace App\Controller;

use App\Entity\SoumissionDevoir;
use App\Form\SoumissionDevoirType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Participant;


class SoumissionDevoirController extends AbstractController
{
    // Afficher toutes les soumissions
    #[Route('/soumission/devoir', name: 'app_soumission_devoir_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $soumissions = $entityManager->getRepository(SoumissionDevoir::class)->findAll();

        return $this->render('soumission_devoir/index.html.twig', [
            'soumission_devoirs' => $soumissions,
        ]);
    }

    // Créer une soumission
    #[Route('/soumission/devoir/new', name: 'app_soumission_devoir_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $soumission = new SoumissionDevoir();

    // Fixer l'ID du participant à 1
    $participant = $entityManager->getRepository(Participant::class)->find(1);
    $soumission->setParticipant($participant);  // Assurez-vous que vous avez un setter pour 'participant'

    $form = $this->createForm(SoumissionDevoirType::class, $soumission);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Récupérer le fichier du formulaire
        $fichier = $form->get('fichier')->getData();

        if ($fichier) {
            // Générer un nom unique pour le fichier
            $newFilename = uniqid().'.'.$fichier->guessExtension();

            // Déplacer le fichier vers un répertoire spécifique
            $fichier->move(
                $this->getParameter('soumissions_directory'), // Répertoire de destination (à définir dans services.yaml)
                $newFilename
            );

            // Mettre à jour le chemin du fichier dans l'entité
            $soumission->setFichier($newFilename);  // Assure-toi que l'entité a un setter pour 'fichier'
        }

        // Ajouter la date de soumission
        $soumission->setDateSoumission(new \DateTime());

        // Persist et flush l'entité
        $entityManager->persist($soumission);
        $entityManager->flush();

        return $this->redirectToRoute('app_soumission_devoir_index');
    }

    return $this->render('soumission_devoir/new.html.twig', [
        'form' => $form->createView(),
    ]);
}

    


    // Modifier une soumission
    #[Route('/soumission/devoir/{id}/edit', name: 'app_soumission_devoir_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $soumission = $entityManager->getRepository(SoumissionDevoir::class)->find($id);
        if (!$soumission) {
            throw $this->createNotFoundException('Soumission de devoir non trouvée');
        }

        $form = $this->createForm(SoumissionDevoirType::class, $soumission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_soumission_devoir_index');
        }

        return $this->render('soumission_devoir/edit.html.twig', [
            'form' => $form->createView(),
            'soumission' => $soumission,
        ]);
    }

    // Voir les détails d'une soumission
    #[Route('/soumission/devoir/{id}', name: 'app_soumission_devoir_show', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $entityManager): Response
    {
        $soumission = $entityManager->getRepository(SoumissionDevoir::class)->find($id);
        if (!$soumission) {
            throw $this->createNotFoundException('Soumission de devoir non trouvée');
        }

        return $this->render('soumission_devoir/show.html.twig', [
            'soumission' => $soumission,
        ]);
    }


// Supprimer une soumission
#[Route('/soumission/devoir/{id}/delete', name: 'app_soumission_devoir_delete', methods: ['GET', 'POST'])]
public function delete(int $id, EntityManagerInterface $entityManager, Request $request): Response
{
    $soumission = $entityManager->getRepository(SoumissionDevoir::class)->find($id);
    
    if (!$soumission) {
        throw $this->createNotFoundException('Soumission de devoir non trouvée');
    }

    if ($this->isCsrfTokenValid('delete'.$soumission->getId(), $request->request->get('_token'))) {
        $entityManager->remove($soumission);
        $entityManager->flush();
    }

    return $this->redirectToRoute('app_soumission_devoir_index');
}


}

