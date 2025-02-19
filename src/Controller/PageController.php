<?php
namespace App\Controller;

use App\Repository\ThemeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class PageController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(ThemeRepository $themeRepository): Response
    {
        // Récupère tous les thèmes
        $themes = $themeRepository->findAll();
        return $this->render('page/index.html.twig', [
            'themes' => $themes,
        ]);
    }

    #[Route('/contact-us', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('page/contact.html.twig', []);
    }

    #[Route('/about-us', name: 'app_about_us')]
    public function aboutUs(): Response
    {
        return $this->render('page/about.html.twig', []);
    }

    #[Route('/service-us', name: 'app_service_us')]
    public function serviceUs(): Response
    {
        return $this->render('page/service.html.twig', []);
    }

    #[Route('/programs-us', name: 'app_programs_us')]
    public function programsUs(): Response
    {
        return $this->render('page/programs.html.twig', []);
    }
    
    
}
