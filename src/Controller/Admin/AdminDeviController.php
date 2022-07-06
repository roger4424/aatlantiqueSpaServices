<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use App\Entity\Devi;
use App\Form\ContactType;
use App\Form\DeviType;
use App\Repository\DeviRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/devi')]
class AdminDeviController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private DeviRepository $deviRepository)
    {
    }

    #[Route(path: '/', name: 'admin_devi_list', methods: ["GET"])]
    public function index(): Response
    {
        // $devis = $this->deviRepository;
        $devis = $this->deviRepository->findBy(['inTrash' => false], ['createdAt' => 'DESC']);
        return $this->render('admin/devi/list.html.twig', [
            'devis' => $devis
        ]);
    }

    #[Route(path: '/detail/{id}', name: 'admin_devi_detail')]
    public function detail(Devi $devi): Response
    {
        return $this->render('admin/devi/detail.html.twig', [
            'devi' => $devi
        ]);
    }

    #[Route(path: '/edit/{id}', name: 'admin_devi_edit', requirements: ["id" => '[0-9]*'], methods: ["GET", "POST"])]
    public function edit(Devi $devi, Request $request): Response
    {
        $form = $this->createForm(DeviType::class, $devi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('admin_devi_success', 'demande de devis bien modifiée');
            return $this->redirectToRoute('admin_devi_list');
        }


        return $this->render('admin/devi/edit.html.twig', [
            'devi' => $devi,
            'form' => $form->createView()
        ]);
    }

    #[Route(path: '/movetotrash/{id}', name: 'admin_devi_movetotrash', requirements: ["id" => '[0-9]*'], methods: ["GET"])]
    public function moveToTrash(Devi $devi): RedirectResponse
    {
        //true !== true-> false
        //false !== true-> true
        $devi->setInTrash($devi->getInTrash() !== true);


        $this->entityManager->flush();
        return $this->redirectToRoute('admin_devi_list');
    }

    #[Route(path: '/clear', name: 'admin_devi_clear', methods: ["GET"])]
    public function clearAll($devis): RedirectResponse
    {
        $contacts = $this->deviRepository->findBy(['inTrash' => false]);

        foreach ($devis as $devi) {
            $devi->setInTrash(true);
        }
        $this->entityManager->flush();
        return $this->redirectToRoute('admin_devi_list');
    }

    #[Route(path: '/trash', name: 'admin_devi_trash', methods: ['GET'])]
    public function showTrash(): Response
    {
        $devis = $this->deviRepository->findBy(['inTrash' => true], ['createdAt' => 'DESC']);
        return $this->render('admin/devi/trash.html.twig', [
            'devis' => $devis,
            'trash_btn' => true
        ]);
    }

    #[Route(path: '/delete/{id}', name: 'admin_devi_delete', requirements: ['id' => '[0-9]*'], methods: ["DELETE"])]
    public function delete(Devi $devi, Request $request): RedirectResponse
    {

        if ($this->isCsrfTokenValid('deletedevi' . $devi->getId(), $request->request->get('_token'))) {
        }
        //si token = token
        $this->entityManager->remove($devi);
        $this->entityManager->flush();
        $this->addFlash('admin_trash_success', 'demande bien supprimée');
        return $this->redirectToRoute('admin_contact_trash');
    }

    #[Route(path: '/edit/{id}/intervention', name: 'admin_devis_edit_intervention', methods: ["PUT"])]
    public function editInterventionStatus(Devi $devi, Request $request): RedirectResponse{
        if($this->isCsrfTokenValid('edit_intervention_statut'.$devi->getId(), $request->request->get('_token'))){
            $devi->setIsInterventionValidated($devi->getIsInterventionValidated()!==true);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('admin_devi_list');
    }


}