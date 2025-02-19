<?php

namespace App\Controller;

use App\Entity\Devoir;
use App\Form\DevoirType;
use App\Repository\DevoirRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted('ROLE_ENSEIGNANT')]
// Seuls les enseignants peuvent accéder aux routes du DevoirController

#[Route('/devoir')]
final class DevoirController extends AbstractController
{
    #[Route(name: 'app_devoir_index', methods: ['GET'])]
    public function index(DevoirRepository $devoirRepository): Response
    {
        return $this->render('devoir/index.html.twig', [
            'devoirs' => $devoirRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_devoir_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $devoir = new Devoir();
    $form = $this->createForm(DevoirType::class, $devoir);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Récupérer l'enseignant (géré par ton ami)
        $enseignant = $entityManager->getRepository(\App\Entity\Enseignant::class)->find(2);
        if (!$enseignant) {
            throw new \LogicException('Aucun enseignant trouvé avec l\'ID 2.');
        }
        
        $devoir->setEnseignant($enseignant);

        // Ajouter automatiquement tous les participants de cet enseignant
        foreach ($enseignant->getParticipants() as $participant) {
            $devoir->addParticipant($participant);
        }

        // Gérer le fichier si nécessaire
        $fichier = $form->get('fichier')->getData();
        if ($fichier) {
            $nomFichier = uniqid() . '.' . $fichier->guessExtension();
            $fichier->move($this->getParameter('devoirs_directory'), $nomFichier);
            $devoir->setFichier($nomFichier);
        }

        $entityManager->persist($devoir);
        $entityManager->flush();

        return $this->redirectToRoute('app_devoir_index');
    }

    return $this->render('devoir/new.html.twig', [
        'form' => $form->createView(),
    ]);
}


    #[Route('/{id}', name: 'app_devoir_show', methods: ['GET'])]
    public function show(Devoir $devoir): Response
    {
        return $this->render('devoir/show.html.twig', [
            'devoir' => $devoir,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_devoir_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Devoir $devoir, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DevoirType::class, $devoir);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_devoir_index');
        }

        return $this->render('devoir/edit.html.twig', [
            'devoir' => $devoir,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_devoir_delete', methods: ['POST'])]
    public function delete(Request $request, Devoir $devoir, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $devoir->getId(), $request->get('_token'))) {
            $entityManager->remove($devoir);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_devoir_index');
    }
}
