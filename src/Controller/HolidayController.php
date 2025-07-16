<?php

namespace App\Controller;

use App\Entity\Holiday;
use App\Entity\Timeslot;
use App\Entity\User;
use App\Form\HolidayType;
use App\Repository\HolidayRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Attribute\Route;

final class HolidayController extends AbstractController
{
    #[Route('/holiday', name: 'app_holiday')]
    public function index(HolidayRepository $holidayRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $holidays = $holidayRepository->findBy(["owner" => $user], ["startTime" => "DESC"]);

        return $this->render('holiday/index.html.twig', [
            'holidays' => $holidays,
        ]);
    }

    #[Route('/holiday/{id}/delete', name: 'app_holiday_delete', methods: ['POST'])]
    public function delete(Holiday $holiday, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$holiday->getId(), $request->getPayload()->getString('_token'))) {
            /** @var User $user */
            $user = $this->getUser();

            if ($user !== $holiday->getOwner()) {
                throw new AccessDeniedHttpException();
            }

            $entityManager->remove($holiday);
            $entityManager->flush();

            $this->addFlash("message", "Congé/jour férié bien supprimé");
        }

        return $this->redirectToRoute('app_holiday', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/holiday/add', name: 'app_holiday_add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $holiday = new Holiday();

        $form = $this->createForm(HolidayType::class,$holiday);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($holiday->getStartTime() > $holiday->getEndTime()) {
                $this->addFlash("error", "La date de début est supérieure à la date de fin");

                return $this->render('holiday/add.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            $holiday->setOwner($user)
                ->setCreatedAt(new \DateTime());

            $entityManager->persist($holiday);
            $entityManager->flush();

            $this->addFlash("message", "Congé/jour férié bien ajouté");

            return $this->redirectToRoute('app_holiday', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('holiday/add.html.twig', [
            'form' => $form-> createView()
        ]);
    }
}
