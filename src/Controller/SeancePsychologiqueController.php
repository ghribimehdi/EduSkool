<?php

namespace App\Controller;

use App\Entity\SeancePsychologique;
use App\Form\SeancePsychologiqueType;
use App\Repository\SeancePsychologiqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class SeancePsychologiqueController extends AbstractController
{
    #[Route('/seance-psychologique', name: 'app_seance_psychologique_index', methods: ['GET'])]
    public function index(SeancePsychologiqueRepository $seancePsychologiqueRepository): Response
    {
        $seances = $seancePsychologiqueRepository->findAll();

        return $this->render('seancepsychologique/index.html.twig', [
          'seances' => $seances,
      ]);
      
    }

    #[Route('/psychologue/demandes', name: 'app_psychologue_demandes', methods: ['GET'])]
    public function psychologueDemandes(SeancePsychologiqueRepository $seancePsychologiqueRepository): Response
    {
        $pendingSessions = $seancePsychologiqueRepository->findBy(['status' => 'pending']);

        return $this->render('SeancePsychologique/demandes.html.twig', [
            'demandesEnAttente' => $pendingSessions,
        ]);
    }

    #[Route('/psychologue/demande/{id}/accepter', name: 'app_psychologue_accepter', methods: ['POST'])]
    public function accepterDemande(SeancePsychologique $seance, EntityManagerInterface $entityManager): Response
    {
        $seance->setStatus('accepted');
        $entityManager->flush();

        return $this->redirectToRoute('app_psychologue_demandes');
    }

    #[Route('/psychologue/demande/{id}/refuser', name: 'app_psychologue_refuser', methods: ['POST'])]
    public function refuserDemande(SeancePsychologique $seance, EntityManagerInterface $entityManager): Response
    {
        $seance->setStatus('refused');
        $entityManager->flush();

        return $this->redirectToRoute('app_psychologue_demandes');
    }

    #[Route('/participant/{participantName}', name: 'app_seance_psychologique_by_participant', methods: ['GET'])]
    public function findByParticipant(string $participantName, SeancePsychologiqueRepository $seancePsychologiqueRepository): Response
    {
        $seances = $seancePsychologiqueRepository->findByParticipant($participantName);
        return $this->render('SeancePsychologique/index.html.twig', [
            'seances' => $seances,
            'filter' => 'Participant: ' . $participantName,
        ]);
    }

    #[Route('/psychologue/{psychologueName}', name: 'app_seance_psychologique_by_psychologue', methods: ['GET'])]
    public function findByNomPsychologue(string $psychologueName, SeancePsychologiqueRepository $seancePsychologiqueRepository): Response
    {
        $seances = $seancePsychologiqueRepository->findByNomPsychologue($psychologueName);
        return $this->render('SeancePsychologique/index.html.twig', [
            'seances' => $seances,
            'filter' => 'Psychologue: ' . $psychologueName,
        ]);
    }
    #[Route('/new', name: 'app_seance_psychologique_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $seance = new SeancePsychologique();
        $seance->setStatus('pending');
        $form = $this->createForm(SeancePsychologiqueType::class, $seance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($seance);
            $entityManager->flush();
            return $this->redirectToRoute('app_seance_psychologique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('SeancePsychologique/new.html.twig', [
            'seance' => $seance,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/type/{type}', name: 'app_seance_psychologique_by_type', methods: ['GET'])]
    public function findByType(string $type, SeancePsychologiqueRepository $seancePsychologiqueRepository): Response
    {
        $seances = $seancePsychologiqueRepository->findByType($type);
        return $this->render('SeancePsychologique/index.html.twig', [
            'seances' => $seances,
            'filter' => 'Type: ' . $type,
        ]);
    }

    #[Route('/{id}', name: 'app_seance_psychologique_show', methods: ['GET'])]
    public function show(SeancePsychologique $seance): Response
    {
        return $this->render('SeancePsychologique/show.html.twig', [
            'seance' => $seance,
        ]);
    }

    #[Route('shows/{id}', name: 'app_seance_psychologique_shows', methods: ['GET'])]
    public function shows(SeancePsychologique $seance): Response
    {
        return $this->render('SeancePsychologique/shows.html.twig', [
            'seance' => $seance,
        ]);
    }
    #[Route('/{id}/edit', name: 'app_seance_psychologique_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SeancePsychologique $seance, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SeancePsychologiqueType::class, $seance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_seance_psychologique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('SeancePsychologique/edit.html.twig', [
            'seance' => $seance,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'app_seance_psychologique_delete', methods: ['POST'])]
    public function delete(Request $request, SeancePsychologique $seance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $seance->getId(), $request->request->get('_token'))) {
            $entityManager->remove($seance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_seance_psychologique_index', [], Response::HTTP_SEE_OTHER);
    }
}
