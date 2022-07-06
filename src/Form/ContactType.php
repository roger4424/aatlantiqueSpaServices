<?php

namespace App\Form;

use App\Entity\Devi;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;


class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label'=> 'Nom',
                'attr'=>[
                    'placeholder'=>'Nom',
                    'class'=> 'form-control rounded-pill'
                ]

            ])
            ->add('prenom', TextType::class, [
                'label'=> 'Prénom',
                'attr'=> ['placeholder'=> 'Prénom',
                    'class'=>'form-control rounded-pill'],
            ])
            ->add('adresse', TextType::class, [
                'label'=> 'Adresse',
                'attr'=> ['placeholder'=> 'Adresse',
                    'class'=>'form-control rounded-pill'],
            ])
            ->add('complement', TextType::class, [
                'label'=> 'Complément',
                'attr'=>['placeholder'=> "Complément d'infos"],
                'help'=> "Renseigner des informations qui vous semblent utiles",
                'required'=> false

            ])
            ->add('phone',null, [
                'label'=> 'Numéro de téléphone',
                'attr'=> ['placeholder'=> 'Téléphone',
                    'class'=>'form-control rounded-pill'],
            ])
            ->add('email', EmailType::class, [
                'label'=>'Adresse mail',
                'attr'=> ['placeholder'=> 'Email',
                    'class'=>'form-control rounded-pill'],
            ])
            ->add('zipcode', TextType::class, [
                'label'=> 'Code postal',
                'attr'=> ['placeholder'=> 'Code postal',
                    'class'=>'form-control rounded-pill'],
            ])
            ->add('ville', TextType::class, [
                'label'=> 'ville',
                'attr'=> ['placeholder'=> 'Ville',
                    'class'=>'form-control rounded-pill'],
            ])
            ->add('message', TextareaType::class, [
                'label'=> 'Message'
            ])
            // callback rappelle la fonction
            ->add('devis', EntityType::class, [
                'class'=> Devi::class,
                'choice_label'=> function($devi){
                    return $devi->getPrenom(). ' ' .$devi->getNom().'#'.$devi->getId();
                },
                'expanded'=>false,
                'multiple'=>true,
                'required'=>false
            ])
            ->add('champ_condition', CheckboxType::class, [
                'label'=>"Accepter les conditions",
                'required'=>true,
                'mapped'=>false,
                'constraints'=>[
                    new IsTrue()
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
