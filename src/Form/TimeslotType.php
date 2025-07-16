<?php

namespace App\Form;

use App\Entity\Timeslot;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TimeslotType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("days", ChoiceType::class, [
                "label" => "Jours",
                "choices" => [
                    "Lundi" => 0,
                    "Mardi" => 1,
                    "Mercredi" => 2,
                    "Jeudi" => 3,
                    "Vendredi" => 4,
                    "Samedi" => 5,
                    "Dimanche" => 6
                ],
                "multiple" => true
            ])
            ->add('startTime', TimeType::class, ["label" => "Début"])
            ->add('endTime', TimeType::class, ["label" => "Fin"])
            ->add('delayBetweenAppointments', IntegerType::class, ["label" => "Délais entre rendez-vous"])
            ->add('numberAppointments', IntegerType::class, ["required" => false, "label" => "Nombre de rendez-vous maximum"])
            ->add("submit",
                SubmitType::class,
                ["label" => "Sauvegarder", "attr" => ["class" => "btn btn-primary"]]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Timeslot::class,
        ]);
    }
}
