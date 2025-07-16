<?php

namespace App\Controller;

use App\Entity\Service;
use App\Entity\Timeslot;
use App\Entity\User;
use App\Form\TimeslotType;
use App\Repository\TimeslotRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Attribute\Route;

final class TimeslotController extends AbstractController
{
    #[Route('/timeslot', name: 'app_timeslot')]
    public function index(TimeslotRepository $timeslotRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $timeslots = $timeslotRepository->findBy(["owner" => $user]);

        return $this->render('timeslot/index.html.twig', [
            'timeslots' => $timeslots,
        ]);
    }

    #[Route('/timeslot/add', name: 'app_timeslot_add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $timeslot = new Timeslot();

        $form = $this->createForm(TimeslotType::class, $timeslot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($timeslot->getStartTime() > $timeslot->getEndTime()) {
                $this->addFlash("error", "La date de début est supérieure à la date de fin");

                return $this->render('timeslot/add.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            $timeslot->setOwner($user);
            $entityManager->persist($timeslot);
            $entityManager->flush();

            $this->addFlash("message", "Créneau bien ajouté");

            return $this->redirectToRoute('app_timeslot');
        }

        return $this->render('timeslot/add.html.twig', [
            'form' => $form->createView()
        ]);
    }


    #[Route('/timeslot/{id}/delete', name: 'app_timeslot_delete', methods: ['POST'])]
    public function delete(Timeslot $timeslot, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$timeslot->getId(), $request->getPayload()->getString('_token'))) {
            /** @var User $user */
            $user = $this->getUser();

            if ($user !== $timeslot->getOwner()) {
                throw new AccessDeniedHttpException();
            }

            $entityManager->remove($timeslot);
            $entityManager->flush();

            $this->addFlash("message", "Créneau bien supprimé");
        }

        return $this->redirectToRoute('app_timeslot', [], Response::HTTP_SEE_OTHER);
    }

}
