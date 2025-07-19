<?php

namespace App\Controller;

use App\Entity\Service;
use App\Repository\UpsellRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

final class ApiController extends AbstractController
{
    #[Route('/api/service/{id}/upsells', name: 'app_api_service_upsells')]
    public function upsells(Service $service, UpsellRepository $upsellRepository, SerializerInterface $serializer): JsonResponse
    {
        $upsells = $upsellRepository->findByService($service);
        $upsellsJson = $serializer->serialize($upsells, 'json', [
            AbstractNormalizer::ATTRIBUTES => ['id', 'name', 'image', 'price', 'duration']
        ]);

        return new JsonResponse($upsellsJson, 200, [], true);
    }
}