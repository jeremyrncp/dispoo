<?php

namespace App\Controller;

use App\Entity\Service;
use App\Entity\User;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use App\Service\FileService;
use App\VO\ServiceVO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
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

    #[Route('/service/add', name: 'app_service_add')]
    public function add(Request $request, FileService $fileService, EntityManagerInterface $entityManager): Response
    {
        $servieVO = new ServiceVO();

        $form = $this->createForm(ServiceType::class, $servieVO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service = new Service();
            $this->copyFileAndHydrate($servieVO, $fileService, $service);

            $entityManager->persist($service);
            $entityManager->flush();

            $this->addFlash("message", "Service bien ajouté");

            return $this->redirectToRoute("app_service");
        }

        return $this->render('service/add.html.twig', [
            "form" => $form->createView()
        ]);
    }


    #[Route('/service/{id}/edit', name: 'app_service_edit')]
    public function edit(Service $service, Request $request, FileService $fileService, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user !== $service->getCategory()->getOwner()) {
            throw new AccessDeniedHttpException();
        }

        $serviceVO = new ServiceVO();
        $serviceVO->image = new File(__DIR__ . "/../../public/uploads/" . $service->getImage());
        $serviceVO->category = $service->getCategory();
        $serviceVO->duration = $service->getDuration();
        $serviceVO->price = $service->getPrice() / 100;
        $serviceVO->name = $service->getName();
        $serviceVO->description = $service->getDescription();

        $form = $this->createForm(ServiceType::class, $serviceVO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->copyFileAndHydrate($serviceVO, $fileService, $service);
            $entityManager->flush();

            $this->addFlash("message", "Service bien modifié");

            return $this->redirectToRoute("app_service");
        }

        return $this->render('service/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/service/{id}/delete', name: 'app_service_delete', methods: ['POST'])]
    public function delete(Service $service, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$service->getId(), $request->getPayload()->getString('_token'))) {
            /** @var User $user */
            $user = $this->getUser();

            if ($user !== $service->getCategory()->getOwner()) {
                throw new AccessDeniedHttpException();
            }

            $entityManager->remove($service);

            unlink(__DIR__ . "/../../public/uploads/". $service->getImage());
            $entityManager->flush();

            $this->addFlash("message", "Service bien supprimé");
        }

        return $this->redirectToRoute('app_service', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * Description extracted function
     *
     * @param ServiceVO   $servieVO
     * @param FileService $fileService
     * @param Service     $service
     *
     * @return void
     */
    public function copyFileAndHydrate(ServiceVO $servieVO, FileService $fileService, Service $service): void
    {
        /** @var File $file */
        $file = $servieVO->image;

        if ($file instanceof File) {
            $nameFile             = $fileService->naming($service);
            $filenameandextension = $fileService->save($file, __DIR__ . "/../../public/uploads/", $nameFile);
            $service->setImage($filenameandextension);
        }

        $service->setDescription($servieVO->description)
            ->setName($servieVO->name)
            ->setPrice($servieVO->price * 100)
            ->setDuration($servieVO->duration)
            ->setCategory($servieVO->category);
    }
}
