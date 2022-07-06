<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route(path: '/admin/contact')]
class AdminContactController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private ContactRepository $contactRepository)
    {
    }

    #[Route(path: '/', name: 'admin_contact_list', methods: ["GET"])]
    public function index(): Response{
        /**/
        $contacts = $this->contactRepository->findBy(['inTrash'=> false],['createdAt'=> 'DESC']);
        return $this->render('admin/contact/list.html.twig', [
            'contacts'=> $contacts
        ]);
    }

 /**   #[Route(path: '/{id}', name: 'admin_contact_detail')]
    public function detail(int $id, ContactRepository $contactRepository): Response{

        $contact = $contactRepository->find($id);
        return $this->render('admin/contact/detail.html.twig', [
            'contact'=> $contact
        ]);
    }*/

    #[Route ("/detail/{id}",name: "admin_contact_detail")]
    public function detail(Contact $contact):Response{

        return $this->render('admin/contact/detail.html.twig',[
            'contact'=> $contact
        ]);
    }

    #[Route(path: '/edit/{id}', name: 'admin_contact_edit', requirements: ["id"=> '[0-9]*'], methods: ["GET", "POST"])]
    public function edit(Contact $contact, Request $request): Response{
        $form =$this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->entityManager->flush();
            $this->addFlash('admin_contact_success', 'demande de contact bien modifiée');
            return $this->redirectToRoute('admin_contact_list');
        }


        return $this->render('admin/contact/edit.html.twig', [
            'contact'=> $contact,
            'form'=>$form->createView()
        ]);
    }
    #[Route(path: '/movetotrash/{id}', name: 'admin_contact_movetotrash',  requirements: ["id"=> '[0-9]*'], methods: ["GET"])]
    public function moveToTrash(Contact $contact): RedirectResponse{
          //true !== true-> false
          //false !== true-> true
        $contact->setInTrash($contact->getInTrash() !== true);


        $this->entityManager->flush();
        return $this->redirectToRoute('admin_contact_list');
    }

    #[Route(path: '/clear', name: 'admin_contact_clear', methods: ["GET"])]
    public function clearAll(): RedirectResponse
    {
        $contacts = $this->contactRepository->findBy(['inTrash' => false]);
        foreach ($contacts as $contact) {
            $contact->setInTrash(true);
        }
        $this->entityManager->flush();
        return $this->redirectToRoute('admin_contact_list');
    }

    #[Route(path: '/trash', name: 'admin_contact_trash', methods: ['GET'])]
    public function showTrash(): Response{
        $contacts = $this->contactRepository->findBy(['inTrash'=>true], ['createdAt'=>'DESC']);
        return $this->render('admin/contact/trash.html.twig', [
            'contacts'=>$contacts,
            'trash_btn'=>true
        ]);
    }

    #[Route(path: '/delete/{id}',name: 'admin_contact_delete', requirements: ['id'=>'[0-9]*'], methods: ["DELETE"])]
    public function delete(Contact $contact, Request $request): RedirectResponse{

        if($this->isCsrfTokenValid('deletecontact'.$contact->getId(), $request->request->get('_token'))){}
        //si token = token
        $this->entityManager->remove($contact);
        $this->entityManager->flush();
        $this->addFlash('admin_trash_success', 'demande bien supprimée');
        return $this->redirectToRoute('admin_contact_trash');
    }


}