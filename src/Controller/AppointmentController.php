<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Entity\User;
use App\Form\ExportAppointmentType;
use App\Repository\AppointmentRepository;
use App\Service\AppointmentService;
use App\VO\ExportAppointmentVO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Attribute\Route;

final class AppointmentController extends AbstractController
{
    #[Route('/appointment', name: 'app_appointment')]
    public function index(AppointmentRepository $appointmentRepository, AppointmentService $appointmentService, Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        //thistweek
        $date = new \DateTime();
        $startOfWeek = clone $date;
        $startOfWeek->modify('monday this week');
        $endOfWeek = clone $startOfWeek;
        $endOfWeek->modify('sunday this week');
        $thisweekappointments = $appointmentRepository->getByUserAndRangeDate($startOfWeek,$endOfWeek, $user);

        //lastweek
        $date = new \DateTime();
        $date->modify("-10 days");
        $startOfLastWeek = clone $date;
        $startOfLastWeek->modify('monday this week');
        $endOfLastWeek = clone $startOfLastWeek;
        $endOfLastWeek->modify('sunday this week');
        $lastweekappointments = $appointmentRepository->getByUserAndRangeDate($startOfLastWeek,$endOfLastWeek, $user);

        $exportAppointmentVO = new ExportAppointmentVO();

        $form = $this->createForm(ExportAppointmentType::class, $exportAppointmentVO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $appointmentService->export($exportAppointmentVO->start, $exportAppointmentVO->end);
            $response = new BinaryFileResponse($file);

            $response->headers->set('Content-Disposition', 'attachment; filename="' . basename($file) . '"');
            $response->headers->set('Content-Type', 'text/csv');

            return $response;
        }


        return $this->render('appointment/index.html.twig', [
            'form' => $form->createView(),
            'CAlastweek' => $appointmentService->getSum($lastweekappointments),
            'CAthisweek' => $appointmentService->getSum($thisweekappointments),
            'appointments' => $appointmentRepository->findByUserAndAboveDate($user, new \DateTime())
        ]);
    }

    #[Route('/appointment-calendar', name: 'app_appointment_calendar')]
    public function calendar(Request $request, AppointmentService $appointmentService, AppointmentRepository $appointmentRepository)
    {
        $exportAppointmentVO = new ExportAppointmentVO();

        $form = $this->createForm(ExportAppointmentType::class, $exportAppointmentVO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $appointmentService->export($exportAppointmentVO->start, $exportAppointmentVO->end);
            $response = new BinaryFileResponse($file);

            $response->headers->set('Content-Disposition', 'attachment; filename="' . basename($file) . '"');
            $response->headers->set('Content-Type', 'text/csv');

            return $response;
        }

        /** @var User $user */
        $user = $this->getUser();

        return $this->render('appointment/calendar.html.twig', [
            'form' => $form->createView(),
            'appointments' => $appointmentRepository->findBy(["owner" => $user])
        ]);
    }
    #[Route('/appointment/{id}/details', name: 'app_appointment_details')]
    public function details(Appointment $appointment)
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($appointment->getOwner() !== $user AND !$this->isGranted("ROLE_ADMIN")) {
            throw new AccessDeniedHttpException();
        }

        return $this->render('appointment/details.html.twig', [
            'appointment' => $appointment,
        ]);
    }

    #[Route('/appointment/{id}/active', name: 'app_appointment_active')]
    public function active(Appointment $appointment, EntityManagerInterface $entityManager)
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($appointment->getOwner() !== $user and !$this->isGranted("ROLE_ADMIN")) {
            throw new AccessDeniedHttpException();
        }

        $appointment->setActive(true);
        $entityManager->flush();;

        //TODO sent email

        return $this->redirectToRoute("app_appointment_details", ["id" => $appointment->getId()]);
    }

    #[Route('/appointment/{id}/deactive', name: 'app_appointment_deactive')]
    public function deactive(Appointment $appointment, EntityManagerInterface $entityManager)
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($appointment->getOwner() !== $user AND !$this->isGranted("ROLE_ADMIN")) {
            throw new AccessDeniedHttpException();
        }

        $appointment->setActive(false);
        $entityManager->flush();;

        //TODO sent email

        return $this->redirectToRoute("app_appointment_details", ["id" => $appointment->getId()]);
    }
}
