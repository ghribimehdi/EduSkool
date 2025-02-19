<?php

namespace App\Controller;

use App\Entity\Pack;
use App\Form\PackType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/pack')]
final class PackController extends AbstractController
{
    #[Route(name: 'app_pack_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $packs = $entityManager
            ->getRepository(Pack::class)
            ->findAll();

        return $this->render('pack/index.html.twig', [
            'packs' => $packs,
        ]);
    }

    #[Route('/new', name: 'app_pack_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pack = new Pack();
        $form = $this->createForm(PackType::class, $pack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pack);
            $entityManager->flush();

            return $this->redirectToRoute('app_pack_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pack/new.html.twig', [
            'pack' => $pack,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pack_show', methods: ['GET'])]
    public function show(Pack $pack): Response
    {
        return $this->render('pack/show.html.twig', [
            'pack' => $pack,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pack_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pack $pack, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PackType::class, $pack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pack_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pack/edit.html.twig', [
            'pack' => $pack,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pack_delete', methods: ['POST'])]
    public function delete(Request $request, Pack $pack, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pack->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($pack);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pack_index', [], Response::HTTP_SEE_OTHER);
    }
}
