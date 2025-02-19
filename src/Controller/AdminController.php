<?php
namespace App\Controller;
use App\Entity\Devoir;
use App\Entity\SoumissionDevoir;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
public function index(EntityManagerInterface $entityManager): Response
{
    $devoirs = $entityManager->getRepository(Devoir::class)->findAll();
    $soumissions = $entityManager->getRepository(SoumissionDevoir::class)->findAll(); // Récupère toutes les soumissions

    return $this->render('admin/index.html.twig', [
        'controller_name' => 'AdminController',
        'devoirs' => $devoirs,  // Passer les devoirs
        'soumissions' => $soumissions, // Passer les soumissions
    ]);
}

#[Route('/admin/devoirs', name: 'admin_devoirs')]
public function listeDevoirs(EntityManagerInterface $entityManager): Response
{
    $devoirs = $entityManager->getRepository(Devoir::class)->findAll();

    return $this->render('admin/devoirs.html.twig', [
        'devoirs' => $devoirs,
    ]);
}

    #[Route('/admin/soumissions', name: 'admin_soumissions')]
    public function listeSoumissions(EntityManagerInterface $entityManager): Response
    {
        $soumissions = $entityManager->getRepository(SoumissionDevoir::class)->findAll();

        return $this->render('admin/soumissions.html.twig', [
            'soumissions' => $soumissions,
        ]);
    }

    #[Route('/admin/devoir/{id}/delete', name: 'admin_devoir_delete', methods: ['POST'])]
    public function supprimerDevoir(int $id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $devoir = $entityManager->getRepository(Devoir::class)->find($id);

        if ($devoir) {
            $entityManager->remove($devoir);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_devoirs');
    }

    #[Route('/admin/soumission/{id}/delete', name: 'admin_soumission_delete', methods: ['POST'])]
    public function supprimerSoumission(int $id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $soumission = $entityManager->getRepository(SoumissionDevoir::class)->find($id);

        if ($soumission) {
            $entityManager->remove($soumission);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_soumissions');
    }
    
}
