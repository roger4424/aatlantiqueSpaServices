<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route(path: '/contact', name: 'main_contact')]
    public  function  index(Request $request, MailerService $mailerService): Response{
        /*mon instance*/
        $contact = new Contact();
        /* formulaire */
        $form = $this->createForm(ContactType::class, $contact);
        /*  */
        $form->remove('devis');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $contact->setInTrash(false); //force attribution de l'objet
            $this->entityManager->persist($contact);
            $this->entityManager->flush();
            $this->addFlash('contact_success', 'demande de contact validée, nous reviendrons vers vous');

            /* recup  infos contact*/
//            $mailerService->sendMail($contact->getEmail(), 'emails/contact.html.twig', [
//                'contact'=> $contact
//            ]);

            return $this->redirectToRoute('main_contact');
        }


        return  $this->render('main/contact.html.twig', [
         'active_page'=> ['contact'],
            'form'=> $form->createView()
        ]);

    }






}