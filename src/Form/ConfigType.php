<?php

namespace App\Form;

use App\Entity\Config;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;


class ConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'label' => 'Nom',
                'attr' => ['placeholder' => 'Nom'],
                'constraints' => [new NotBlank()],
                'required' => true
            ])
            ->add('prenom', null, [
                'label' => 'Prénom',
                'attr' => ['placeholder' => 'Prénom'],
                'constraints' => [new NotBlank()],
                'required' => true
            ])
            ->add('mail', EmailType::class, [
                'label' => 'Email',
                'attr' => ['placeholder' => 'Email'],
                'constraints' => [new NotBlank()],
                'required' => true
            ])
            ->add('telephone', null, [
                'label' => 'Téléphone',
                'attr' => ['placeholder' => 'Téléphone'],
                'constraints' => [new NotBlank()],
                'required' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Config::class,
        ]);
    }
}
