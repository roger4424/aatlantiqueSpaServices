<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label'=> 'adresse email'
            ])
            ->add('roles', ChoiceType::class, [
                'label'=> "role de l'utilisateur",
                'choices'=> [
                    'Administrateur'=>'ROLE_ADMIN' ,
                    'Modérateur'=>'ROLE_MODERATEUR'
                ],
                'expanded'=>true,
                'multiple'=>true,
                'required'=>false,
                'help'=>"laissez vide par défaut"

            ])
            ->add('password', RepeatedType::class, [
                'type'=>PasswordType::class,
                'first_options'=> [
                    'label'=>'mot de passe',
                ],
                'second_options'=>[
                    'label'=> "confirmation du mot de passe",
                ],
                'required'=>true,
                'constraints' => [new NotBlank()]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
