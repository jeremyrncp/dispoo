<?php

namespace App\Form;

use App\Entity\Category;
use App\VO\ServiceVO;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceType extends AbstractType
{
    public function __construct(private readonly Security $security)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', FileType::class, ["label" => "Image"])
            ->add("name", TextType::class, ["label" => "Nom"])
            ->add("duration", NumberType::class, ["label" => "Duration"])
            ->add("price", NumberType::class, ["label" => "Prix"])
            ->add("description", TextType::class, ["label" => "Description"])
            ->add("category", EntityType::class, [
                'class' => Category::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->andWhere("c.owner = :user")
                        ->setParameter("user", $this->security->getUser())
                        ->orderBy('c.name', 'ASC');
                },
                'choice_label' => 'name',
                "label" => "Catégorie"
            ])
            ->add("submit",
                SubmitType::class,
                ["label" => "Sauvegarder", "attr" => ["class" => "btn btn-primary"]]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ServiceVO::class,
        ]);
    }
}
