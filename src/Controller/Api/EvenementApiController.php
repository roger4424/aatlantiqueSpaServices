<?php

namespace App\Controller\Api;

use App\Entity\Evenement;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_ADMIN')]
#[Route(path: '/api/event')]
class EvenementApiController extends AbstractController
{

    public function __construct(private  EntityManagerInterface $entityManager)
    {
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/edit/{id}', name: 'api_event_edit', requirements: ['id'=>'[0-9]*'], methods: ["PUT"])]
    public function edit(Evenement $evenement, Request $request): Response{

        $jsonRequest = json_decode($request->getContent(), true);

        if ($jsonRequest['start']) {
            $evenement->setBeginAt(new \DateTime($jsonRequest['start']));
            $evenement->setEndAt(new \DateTime($jsonRequest['end']));

            $this->entityManager->flush();
        }
//        //logger permet de voir ce que l'on a
//        $logger->alert('API', [
//            $jsonRequest['start'],
//            $jsonRequest['end'],
//        ]);

        return new Response(null, 200);
    }
}