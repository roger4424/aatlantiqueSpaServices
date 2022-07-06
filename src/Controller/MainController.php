<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main_home')]
        public function home(){
            return $this->render('main/home.html.twig', [
                'active_page'=> ['home']
            ]);
        }
    #[Route ("/test",name: "main_test")]
    public function test(){
        return $this->render('main/test.html.twig');
    }

    #[Route ("/cgu",name: "main_cgu")]
    public function cgu(){
        return $this->render('main/cgu.html.twig');
    }
}