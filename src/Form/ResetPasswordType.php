<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('password', PasswordType::class,
            [
                'label' => 'Mot de passe',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit compoter {{ limit }} charactères',
                        'max' => 255,
                    ]),
                    new Regex([
                        'pattern' => '/\d+/i',
                    ]),
                    new Regex([
                        'pattern' => '/[,#?!@$%^&*-]+/i',
                    ]),
                ],
            ])
                ->add('submit', SubmitType::class, ['label' => 'Sauvegarder', "attr" => ["class" => "btn btn-primary"]])
        ;
    }
}
