<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserSearchType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/users')]
class AdminUserController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository)
    {
    }

    #[Route('/', name: 'admin_users_list')]
    public function index(Request$request): Response
    {
        /* barre de recherche */
        $searchForm = $this->createForm(UserSearchType::class);
      $searchForm->handleRequest($request);
            //
            $users = $this->userRepository->findByEmail($searchForm->get('search')->getData());

        return $this->render('admin/user/index.html.twig', [
            'active_page' => ['admin_users'],
            'users'=> $users,
            'searchForm'=> $searchForm->createView()
        ]);
    }

    #[Route(path: '/create', name: 'admin_users_create', methods: ["GET", "POST"])]
    public function create(Request $request, UserPasswordHasherInterface $passwordHasher):Response{

        $user = new User();

        $form = $this->createForm(UserType::class, $user);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $this->redirectToRoute('admin_users_list');
        }


        return $this->render('admin/user/create.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    #[Route(path: '/edit/{id}', name: 'admin_users_edit', requirements: ['id'=> '[0-9]*'])]
    public function edit(User $user, Request $request, UserPasswordHasherInterface $passwordHasher):Response{

        if ($user === $this->getUser()){
            return $this->redirectToRoute('admin_users_list');
        }

        $form = $this->createForm(UserType::class, $user);

        $form->remove('password');

        $form->add('password', RepeatedType::class, [
            'type'=>PasswordType::class,
            'first_options'=> [
                'label'=>'mot de passe',
            ],
            'second_options'=>[
                'label'=> "confirmation du mot de passe",
            ],
            'required'=>false,
            'mapped'=> false
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            if (!$form->get('password')->isEmpty()){
                $user->setPassword($passwordHasher->hashPassword($user, $form->get('password')->getData()));
            }


            $this->entityManager->flush();
            return $this->redirectToRoute('admin_users_list');
        }

        return $this->render('admin/user/edit.html.twig', [
            'form'=>$form->createView()
        ]);

        }

}
