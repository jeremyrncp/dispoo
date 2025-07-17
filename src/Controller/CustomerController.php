<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\AppointmentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CustomerController extends AbstractController
{
    #[Route('/customer', name: 'app_customer')]
    public function index(AppointmentRepository $appointmentRepository, Request $request, PaginatorInterface $paginator): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $pagination = $paginator->paginate(
            $appointmentRepository->findBy(["owner" => $user], ["startDateTime" => "DESC"]),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('customer/index.html.twig', [
            'appointments' => $pagination,
        ]);
    }
}
