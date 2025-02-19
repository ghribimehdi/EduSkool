<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Form\CoursType;
use App\Repository\CoursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/cours')]
final class CoursController extends AbstractController
{
    #[Route(name: 'app_cours_index', methods: ['GET'])]
    public function index(CoursRepository $coursRepository): Response
    {
        return $this->render('cours/index.html.twig', [
            'cours' => $coursRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_cours_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $cours = new Cours();
        $form = $this->createForm(CoursType::class, $cours);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            // Validation manuelle en plus du form
            $errors = $validator->validate($cours);
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    $this->addFlash('danger', $error->getMessage());
                }
            } elseif ($form->isValid()) {
                $entityManager->persist($cours);
                $entityManager->flush();

                $this->addFlash('success', 'Cours ajouté avec succès !');
                return $this->redirectToRoute('app_cours_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('cours/new.html.twig', [
            'cours' => $cours,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_cours_show', methods: ['GET'])]
    public function show(Cours $cours): Response
    {
        return $this->render('cours/show.html.twig', [
            'cours' => $cours,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cours_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cours $cours, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $form = $this->createForm(CoursType::class, $cours);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            // Validation manuelle en plus du form
            $errors = $validator->validate($cours);
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    $this->addFlash('danger', $error->getMessage());
                }
            } elseif ($form->isValid()) {
                $entityManager->flush();

                $this->addFlash('success', 'Cours mis à jour avec succès !');
                return $this->redirectToRoute('app_cours_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('cours/edit.html.twig', [
            'cours' => $cours,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_cours_delete', methods: ['POST'])]
    public function delete(Request $request, Cours $cours, EntityManagerInterface $entityManager): Response
    {
        // Récupération du token via $request->request->get('_token')
        if ($this->isCsrfTokenValid('delete' . $cours->getId(), $request->request->get('_token'))) {
            $entityManager->remove($cours);
            $entityManager->flush();

            $this->addFlash('success', 'Cours supprimé avec succès !');
        } else {
            $this->addFlash('danger', 'Token CSRF invalide, suppression annulée.');
        }

        return $this->redirectToRoute('app_cours_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/list', name: 'app_cours_list', methods: ['GET'])]
public function list(CoursRepository $coursRepository): Response
{
    return $this->render('cours/list.html.twig', [
        'cours' => $coursRepository->findAll(),
    ]);
}

}
