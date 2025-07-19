<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WebsiteController extends AbstractController
{
    #[Route('/website/{id}', name: 'app_website')]
    public function index(User $user): Response
    {
        return $this->render('website/index.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/website/{id}/step1', name: 'app_website_step1')]
    public function step1(User $user): Response
    {
        return $this->render('website/step1.html.twig', [
            'user' => $user
        ]);
    }
}
