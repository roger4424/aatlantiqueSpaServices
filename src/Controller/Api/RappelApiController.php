<?php

namespace App\Controller\Api;

use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

class RappelApiController extends AbstractController
{
    public function __construct(private MailerService $mailerService)
    {
    }

    #[Route(path: '/api/rappel', name: 'api_rappel', methods: ["POST"])]
    public function handleData(Request $request): JsonResponse{

        if ($request->request->get('nom') && $request->request->get('phone')){
            try {
                $this->mailerService->sendMail(
                    'noreply@atlantiquespa.com',
                    'emails/rappel.html.twig',
                    [
                        'nom'=> $request->request->get('nom'),
                        'phone'=> $request->request->get('phone')
                    ], 'Nouvelle demande de rappel depuis atlantiqueSpa'
                );

            }catch (TransportExceptionInterface $e){
                return $this->json([
                    'message'=>'Une erreur s\'est produite.'
                ],500);
            }

            return $this->json([
                   'message'=>'vous serez rappelé prochainement.'
                ]);
        }

        return $this->json([
            'message'=> 'Les données sont invalides.'
        ], 400);
    }
}