<?php

namespace App\Form;

use App\Entity\Devi;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichFileType;

class DeviType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',null, [
                'label'=> 'Nom',
                'attr'=> ['placeholder'=> 'Nom',
                    'class'=>'form-control rounded-pill'],
            ])
            ->add('prenom', null, [
                'label'=> 'Prénom',
                'attr'=> ['placeholder'=> 'Prénom',
                    'class'=>'form-control rounded-pill'],
            ])
            ->add('phone',null, [
                'label'=> 'Téléphone',
                'attr'=> ['placeholder'=> 'Téléphone',
                    'class'=>'form-control rounded-pill'],
            ])
//            ->add('adresse',null, [
//                'label'=> 'adresse',
//                'attr'=> ['placeholder'=> 'Adresse',
//                    'class'=>'form-control rounded-pill'],
//            ])

            ->add('adresse', TextType::class, [
                'label'=> 'Adresse',
                'attr'=> ['placeholder'=> 'Adresse',
                    'class'=>'form-control rounded-pill'],
            ])

            ->add('email', EmailType::class, [
                'label'=>'mail',
                'attr'=> ['placeholder'=> 'Email',
                    'class'=>'form-control rounded-pill'],
            ])
            ->add('type', ChoiceType::class, [
                'label'=>"Type d'intervention",
                'choices'=>[
                    'Installation'=> 'Installation',
                    'Entretien'=>'Entretien',
                    'SAV'=>'sav'
                ]
            ])
            /*->add('marque',null, [
                'label'=> 'Marque',
                'attr'=> ['placeholder'=> 'Marque']
            ])*/

            ->add('devisFile', VichFileType::class, [
                'label'=> "Vous pouvez joindre des photos d'accès ou emplacement",
                'allow_delete'=> true,
                'delete_label'=>'Supprimer le fichier',
                'download_uri'=> true,
                'download_label'=> 'Télécharger',
                'required'=> false,
            ])
            ->add('interventionAt', DateTimeType::class, [
                'label'=> "Disponibilité pour l'intervention",
                'date_widget'=> 'single_text',
                'input'=>'datetime_immutable',

            ])
            ->add('status', ChoiceType::class, [
                'label'=> 'Statut du devis',
                'choices'=> [
                    'En attente '=> 'En attente',
                    'En cours'=> 'en cours ',
                    'effectué'=>'effectué'
                ],
                'constraints'=> [new NotBlank()]/* fais sauter la contrainte*/
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Devi::class,
        ]);
    }
}
