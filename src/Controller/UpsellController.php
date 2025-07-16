<?php

namespace App\Controller;

use App\Entity\Service;
use App\Entity\Upsell;
use App\Entity\User;
use App\Form\ServiceType;
use App\Form\UpsellType;
use App\Repository\ServiceRepository;
use App\Repository\UpsellRepository;
use App\Service\FileService;
use App\VO\ServiceVO;
use App\VO\UpsellVO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Attribute\Route;

final class UpsellController extends AbstractController
{
    #[Route('/upsell', name: 'app_upsell')]
    public function index(UpsellRepository $upsellRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $upsells = $upsellRepository->findByUser($user);

        return $this->render('upsell/index.html.twig', [
            'upsells' => $upsells,
        ]);

    }

    #[Route('/upsell/add', name: 'app_upsell_add')]
    public function add(Request $request, EntityManagerInterface $entityManager, FileService $fileService): Response
    {
        $upsellVO = new UpsellVO();

        $form = $this->createForm(UpsellType::class, $upsellVO);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $upsell = new Upsell();
            $upsell->setName($upsellVO->name);

            /** @var File $file */
            $file = $upsellVO->image;

            if ($file instanceof File) {
                $nameFile             = $fileService->naming();
                $filenameandextension = $fileService->save($file, __DIR__ . "/../../public/uploads/", $nameFile);
                $upsell->setImage($filenameandextension);
            }

            $upsell->setPrice($upsellVO->price * 100)
                ->setDuration($upsellVO->duration)
                ->setPosition($upsellVO->position)
                ->setDescription($upsellVO->description);

            foreach ($upsellVO->services as $service) {
                $upsell->addService($service);
            }

            $entityManager->persist($upsell);
            $entityManager->flush();

            $this->addFlash('message', 'Upsell bien ajouté');

            return $this->redirectToRoute('app_upsell');
        }

        return $this->render('upsell/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/upsell/{id}/edit', name: 'app_upsell_edit')]
    public function edit(Upsell $upsell, Request $request, FileService $fileService, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        foreach ($upsell->getServices() as $service) {
            if ($user !== $service->getCategory()->getOwner()) {
                throw new AccessDeniedHttpException();
            }
        }

        $upsellVO = new UpsellVO();
        $upsellVO->duration = $upsell->getDuration();
        $upsellVO->price = $upsell->getPrice() / 100;
        $upsellVO->name = $upsell->getName();
        $upsellVO->position = $upsell->getPosition();
        $upsellVO->services = $upsell->getServices()->toArray();

        $form = $this->createForm(UpsellType::class, $upsellVO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $upsell->setName($upsellVO->name);

            /** @var File $file */
            $file = $upsellVO->image;

            if ($file instanceof File) {
                $nameFile             = $fileService->naming();
                $filenameandextension = $fileService->save($file, __DIR__ . "/../../public/uploads/", $nameFile);
                $upsell->setImage($filenameandextension);
            }

            $upsell->setPrice($upsellVO->price * 100)
                ->setDuration($upsellVO->duration)
                ->setPosition($upsellVO->position)
                ->setDescription($upsellVO->description);

            foreach ($upsell->getServices() as $service) {
                $upsell->removeService($service);
            }

            foreach ($upsellVO->services as $service) {
                $upsell->addService($service);
            }

            $entityManager->flush();
            $this->addFlash("message", "Upsell bien modifié");

            return $this->redirectToRoute("app_upsell");
        }

        return $this->render('upsell/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/upsell/{id}/delete', name: 'app_upsell_delete', methods: ['POST'])]
    public function delete(Upsell $upsell, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$upsell->getId(), $request->getPayload()->getString('_token'))) {
            /** @var User $user */
            $user = $this->getUser();

            foreach ($upsell->getServices() as $service) {
                if ($user !== $service->getCategory()->getOwner()) {
                    throw new AccessDeniedHttpException();
                }
            }

            $entityManager->remove($upsell);

            unlink(__DIR__ . "/../../public/uploads/". $upsell->getImage());
            $entityManager->flush();

            $this->addFlash("message", "Upsell bien supprimé");
        }

        return $this->redirectToRoute('app_upsell', [], Response::HTTP_SEE_OTHER);
    }

}
