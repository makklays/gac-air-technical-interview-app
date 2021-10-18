<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\MessageDigestPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Security\Core\User\LegacyPasswordAuthenticatedUserInterface;

class UserController extends AbstractController
{
    #[Route('/backend/users', name: 'users')]
    public function index(): Response
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/backend/user/add', name: 'user_add')]
    public function new(Request $request): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();

            // hasher
            $weakHasher = new MessageDigestPasswordHasher('md5', true, 1);
            $hashers = [
                User::class => $weakHasher,
            ];
            $hasherFactory = new PasswordHasherFactory($hashers);
            $uph = new UserPasswordHasher($hasherFactory);

            // password
            $password = $uph->hashPassword($user, $user->getPassword());
            //$password = md5($user->getPassword());
            $user->setPassword($password);
            $user->setCreatedAt( new \DateTime() );

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('users', [],302);
        }

        return $this->renderForm('user/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/backend/user/edit/{id}', name: 'user_edit')]
    public function update(int $id, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id '.$id
            );
        }

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();

            // hasher
            $weakHasher = new MessageDigestPasswordHasher('md5', true, 1);
            $hashers = [
                User::class => $weakHasher,
            ];
            $hasherFactory = new PasswordHasherFactory($hashers);
            $uph = new UserPasswordHasher($hasherFactory);

            // password
            $password = $uph->hashPassword($user, $user->getPassword());
            //$password = md5($user->getPassword());
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_show', ['id' => $user->getId()],302);
        }

        return $this->renderForm('user/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/backend/user/{id}', name: 'user_show')]
    public function show(int $id): Response
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/backend/user/delete/{id}', name: 'user_del')]
    public function delete($id): Response
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('users', [],302);
    }
}
