<?php

namespace App\Controller\Admin;

use App\Form\ConfigType;
use App\Repository\ConfigRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route(path: '/admin')]

class AdminConfigController extends AbstractController
{


    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route(path: '/config', name: 'admin_config')]
    public function editConfig(ConfigRepository $configRepository, Request $request): Response
    {
        $config = $configRepository->findOneBy([], ['id' => 'DESC']);

        $form = $this->createForm(ConfigType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('config_success', 'La configuration a bien été  enregistrée.');

            return $this->redirectToRoute('admin_config');
        }


        return $this->render('admin/config.html.twig', [
            'config' => $config,
            'form' => $form->createView()
        ]);
    }
}
