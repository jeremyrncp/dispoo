<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\CompanyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ParametersController extends AbstractController
{
    #[Route('/parameters', name: 'app_parameters')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(CompanyType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash("message", "Nom de société mis à jour");
        }

        return $this->render('parameters/index.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    #[Route('/parameters/mailing/activate', name: 'app_parameters_mailing_activate')]
    public function mailingActivate(EntityManagerInterface $entityManager)
    {
        /** @var User $user */
        $user = $this->getUser();

        $user->setMailing(true);
        $entityManager->flush();

        $this->addFlash("message", "Les emails de rappel ont bien été activés");

        return $this->redirectToRoute("app_parameters");
    }

    #[Route('/parameters/mailing/deactivate', name: 'app_parameters_mailing_deactivate')]
    public function mailingDeactivate(EntityManagerInterface $entityManager)
    {
        /** @var User $user */
        $user = $this->getUser();

        $user->setMailing(false);
        $entityManager->flush();

        $this->addFlash("message", "Les emails de rappel ont bien été désactivés");

        return $this->redirectToRoute("app_parameters");
    }
}
