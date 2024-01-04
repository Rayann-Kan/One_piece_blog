<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

class EditUserPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('currentPassword', PasswordType::class ,[
                "mapped" => false,
                "constraints"=> [
                    new UserPassword(['message' =>'Le mot de passe actuel est incorrect.'])
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Le mot de passe est obligatoire',
                        ]),
                        new Length([
                            'min' => 12,
                            'minMessage' => 'Le mot de passe ne doit pas être inférieur a {{ limit }} caractères',
                            'minMessage' => 'Le mot de passe ne doit pas dépasser {{ limit }} caractères',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                        new Regex([
                            'pattern'=> "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{12,255}$/",
                            'match'=> true,
                            "message"=> 'Le mot de passe doit contenir au moins 12 caractères, au moins une majuscule, une minuscule et un chiffre ou caractère spécial',
                        ])
                    ],
                ],

                'invalid_message' => 'Le mot de passe et sa confirmation doivent être identiques.',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // 'data_class' => User::class,
        ]);
    }
}
