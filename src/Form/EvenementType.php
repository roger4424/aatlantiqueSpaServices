<?php

namespace App\Form;

use App\Entity\Evenement;
use Doctrine\DBAL\Types\DateTimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'label'=> "Le nom de l'évènement",
                'attr'=> ['placeholder'=> 'Nom de l\évenement'],
                'constraints'=> [new NotBlank()]
            ])
            ->add('beginAt', \Symfony\Component\Form\Extension\Core\Type\DateTimeType::class, [
                'label'=> 'Debut',
                'date_widget'=> 'single_text',
                'time_widget'=> 'single_text',
                'constraints'=> [new NotBlank()]

            ])
            ->add('endAt', \Symfony\Component\Form\Extension\Core\Type\DateTimeType::class, [
                'label'=> 'Fin',
                'date_widget'=> 'single_text',
                'time_widget'=> 'single_text',
                'required'=> false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
