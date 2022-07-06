<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin')]
class AdminMainController extends AbstractController
{
    #[Route(path: '/', name: 'admin_main')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
        ]);
    }
}
