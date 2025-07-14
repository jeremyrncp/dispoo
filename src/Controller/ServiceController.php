<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ServiceController extends AbstractController
{
    #[Route('/service', name: 'app_service')]
    public function index(ServiceRepository $serviceRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $services = $serviceRepository->findByUser($user);

        return $this->render('service/index.html.twig', [
            'services' => $services,
        ]);
    }
}
