<?php

namespace App\Controller\Admin;

use App\Entity\Evenement;
use App\Form\EvenementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin')]
class AdminCalendrierController extends AbstractController
{
    #[Route('/calendrier', name: 'admin_calendrier')]
    public function index(): Response
    {
        return $this->render('admin/calendrier/index.html.twig', [
            'active_page'=> ['admin_calendrier']
        ]);
    }

    #[Route(path: '/calendrier/new', name: 'admin_calendrier_event_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response{

        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($evenement);
            $entityManager->flush();
            $this->addFlash('success', "L'évènement a bien été créé");

            return  $this->redirectToRoute('admin_calendrier');
        }
        return $this->render('admin/calendrier/new.html.twig', [
            'form'=> $form->createView()
        ]);
    }

    #[Route(path: '/calendrier/event/{id}', name: 'admin_calendrier_event_show', requirements: ['id'=> '[0-9]*'])]
    public function showEvent(Evenement $evenement): Response{

        return $this->render('admin/calendrier/show.html.twig',[
            'evenement'=>$evenement
        ]);
    }

    #[Route(path: '/calendrier/event/delete/{id}', name: 'admin_calendrier_event_delete', requirements: ['id'=> '[0-9]*'], methods: ["DELETE"])]
    public function delete(Evenement $evenement, Request $request, EntityManagerInterface $entityManager): RedirectResponse{
        if ($this->isCsrfTokenValid('deleteevent'.$evenement->getId(), $request->request->get('_token'))){
            $entityManager->remove($evenement);
            $entityManager->flush();
            $this->addFlash('success', "L'évènenment a bien été supprimé!");
        }
        return $this->redirectToRoute('admin_calendrier');
    }
}
