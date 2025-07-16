<?php

namespace App\Form;

use App\Entity\Service;
use App\VO\UpsellVO;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpsellType extends AbstractType
{
    public function __construct(private readonly Security $security)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', FileType::class, ["label" => "Image"])
            ->add("name", TextType::class, ["label" => "Nom"])
            ->add("duration", IntegerType::class, ["label" => "Durée (minutes)"])
            ->add("price", NumberType::class, ["label" => "Prix (euros)"])
            ->add("description", TextType::class)
            ->add("position", IntegerType::class, ["label" => "Position"])
            ->add("services", EntityType::class, [
                'class' => Service::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->innerJoin("s.category", "c")
                        ->andWhere("c.owner = :user")
                        ->setParameter("user", $this->security->getUser())
                        ->orderBy('s.name', 'ASC');
                },
                "multiple" => true,
                'choice_label' => 'name',
                "label" => "Services"
            ])
            ->add("submit",
                SubmitType::class,
                ["label" => "Sauvegarder", "attr" => ["class" => "btn btn-primary"]]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UpsellVO::class,
        ]);
    }
}
