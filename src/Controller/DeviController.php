<?php

namespace App\Controller;

use App\Entity\Devi;
use App\Entity\Evenement;
use App\Form\DeviType;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

class DeviController extends AbstractController
{
        public function __construct(private EntityManagerInterface $entityManager)
        {
        }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route(path: '/devi', name: 'main_devis')]
        public function index(Request $request, MailerService $mailerService): Response{

            $devi =  new Devi();
            $form = $this->createForm(DeviType::class, $devi);
            $form->remove('status');
            $form->handleRequest($request);

            if ($form->isSubmitted()){

                //verification de contrainte d'antidatage
                $now = new \DateTimeImmutable();
                $interventionAt = $devi->getInterventionAt();
                if ($interventionAt < $now){
                    $form->get('interventionAt')->addError(new FormError('La date ne doit pas être inferieure à la date actuelle'));
                }
            }



            if ($form->isSubmitted() && $form->isValid()){

                $devi->setStatus('En attente');  /* evite de retourner du vide*/

                /* nouveau*/
                $devi->setIsInterventionValidated(false);
                $evenement = new Evenement();
                $evenement
                    /*->setTitle('Intervention'.$devi->getPrenom(). ' '.$devi->getNom())*/
                    ->setTitle('Intervention'.$devi->getFullName())
                    ->setBeginAt(\DateTime::createFromImmutable($devi->getInterventionAt()));
//                    ->setBeginAt($devi->getInterventionAt());
                $devi->setEvenement($evenement);


                $mailerService->sendMail($devi->getEmail(), 'emails/devi.html.twig', [
                    'devi'=> $devi
                ]);

                $this->entityManager->persist($devi);

                $this->entityManager->persist($evenement);
                $this->entityManager->flush();
                $this->addFlash('devi_success', 'demande de devis validée, nous reviendrons vers vous');

               return $this->redirectToRoute('main_devis');

            }

            return  $this->render('main/devis.html.twig', [
                'active_page'=> ['devi'],
                'form'=> $form->createView()
            ]);
        }
}