<?php

namespace App\Service;

use App\Entity\Appointment;
use App\Entity\User;
use App\Repository\AppointmentRepository;
use Symfony\Bundle\SecurityBundle\Security;

class AppointmentService
{
    public function __construct(
        private readonly AppointmentRepository $appointmentRepository,
        private readonly Security $security
    ) {
    }

    public function getSum(array $appointments): int
    {
        $sum = 0;

        /** @var Appointment $appointment */
        foreach ($appointments as $appointment) {
            $sum += $appointment->getPriceCents();
        }

        return $sum;
    }

    public function export(\DateTime $start, \DateTime $end): string
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $appointments = $this->appointmentRepository->getByUserAndRangeDate($start, $end, $user);

        $export = [];

        $export[] = $this->getExpportHeaders();

        /** @var Appointment $appointment */
        foreach ($appointments as $appointment) {
            $export[] = $appointment->getStartDateTime()->format('Y-m-d H:i:s') . ";" .
                $appointment->getEndDateTime()->format('Y-m-d H:i:s') . ";" .
                $appointment->getPriceCents() / 100 . ";" .
                $appointment->getDuration() . ";" .
                $appointment->getFirstname() . ";" .
                $appointment->getLastname() . ";" .
                $appointment->getAddress() . ";" .
                $appointment->getPostalCode() . ";" .
                $appointment->getCity() . ";" .
                $appointment->getPhoneNumber() . ";" .
                $appointment->getEmail() . ";" .
                $appointment->getListAppointmentItems();
        }


        $data = implode(PHP_EOL, $export);

        $fileName = "export_" . uniqid().".csv";

        file_put_contents(__DIR__ . "/../../public/uploads/" . $fileName, $data);

        return __DIR__ . "/../../public/uploads/" . $fileName;
    }

    private function getExpportHeaders(): string
    {
        return "Date début;Date fin;Prix;Durée;Prénom;Nom;Adresse;Code postal;Ville;Telephone;Email;Prestations";
    }

}
