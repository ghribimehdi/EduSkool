<?php

namespace App\Controller;

use App\Entity\SuiviPsychologique;
use App\Form\SuiviPsychologiqueType;
use App\Repository\SuiviPsychologiqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/suivi-psychologique')]
class SuiviPsychologiqueController extends AbstractController
{
    // Afficher la liste des suivis psychologiques
    #[Route('/', name: 'app_suivi_psychologique_index', methods: ['GET'])]
    public function index(SuiviPsychologiqueRepository $suiviPsychologiqueRepository): Response
    {
        return $this->render('SuiviPsychologiqueType/index.html.twig', [
            'suivis' => $suiviPsychologiqueRepository->findAll(),
        ]);
    }

    // Filtrer les suivis par patient
    #[Route('/patient/{patientName}', name: 'app_suivi_psychologique_by_patient', methods: ['GET'])]
    public function findByPatient(string $patientName, SuiviPsychologiqueRepository $suiviPsychologiqueRepository): Response
    {
        $suivis = $suiviPsychologiqueRepository->findByPatient($patientName);
        return $this->render('SuiviPsychologiqueType/index.html.twig', [
            'suivis' => $suivis,
            'filter' => 'Patient: ' . $patientName,
        ]);
    }

    // Filtrer les suivis par psychologue
    #[Route('/psychologue/{psychologueName}', name: 'app_suivi_psychologique_by_psychologue', methods: ['GET'])]
    public function findByPsychologue(string $psychologueName, SuiviPsychologiqueRepository $suiviPsychologiqueRepository): Response
    {
        $suivis = $suiviPsychologiqueRepository->findByPsychologue($psychologueName);
        return $this->render('SuiviPsychologiqueType/index.html.twig', [
            'suivis' => $suivis,
            'filter' => 'Psychologue: ' . $psychologueName,
        ]);
    }

    // Ajouter un nouveau suivi psychologique
    #[Route('/new', name: 'app_suivi_psychologique_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $suivi = new SuiviPsychologique();
        $form = $this->createForm(SuiviPsychologiqueType::class, $suivi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($suivi);
            $entityManager->flush();
            return $this->redirectToRoute('app_suivi_psychologique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('SuiviPsychologiqueType/new.html.twig', [
            'suivi' => $suivi,
            'form' => $form->createView(),
        ]);
    }

    // Afficher un suivi psychologique spécifique
    #[Route('/{id}', name: 'app_suivi_psychologique_show', methods: ['GET'])]
    public function show(SuiviPsychologique $suivi): Response
    {
        return $this->render('SuiviPsychologiqueType/show.html.twig', [
            'suivi' => $suivi,
        ]);
    }

    // Modifier un suivi psychologique existant
    #[Route('/{id}/edit', name: 'app_suivi_psychologique_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SuiviPsychologique $suivi, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SuiviPsychologiqueType::class, $suivi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_suivi_psychologique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('SuiviPsychologiqueType/edit.html.twig', [
            'suivi' => $suivi,
            'form' => $form->createView(),
        ]);
    }

    // Supprimer un suivi psychologique
    #[Route('/{id}/delete', name: 'app_suivi_psychologique_delete', methods: ['POST'])]
    public function delete(Request $request, SuiviPsychologique $suivi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $suivi->getId(), $request->request->get('_token'))) {
            $entityManager->remove($suivi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_suivi_psychologique_index', [], Response::HTTP_SEE_OTHER);
    }
}