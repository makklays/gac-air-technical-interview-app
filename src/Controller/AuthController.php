<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\MessageDigestPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;

class AuthController extends AbstractController
{
    #[Route('/sign-up', name: 'sign-up')]
    public function signup(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $em = $this->getDoctrine()->getManager();

        if ($request->isMethod('post')) {

            $user = new User();
            $user->setUsername($request->get('username'));

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
            $user->setActive(true);
            $user->setCreatedAt( new \DateTime() );

            $em->persist($user);
            $em->flush();
        }

        // get the login error if there is one
        /*$error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();*/

        return $this->render('auth/sign-up.html.twig', [
            //'last_username' => $lastUsername,
            //'error'         => $error,
        ]);
    }

    #[Route('/sign-in', name: 'sign-in')]
    public function signin(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        if ($request->isMethod('post')) {

            // TODO validate
            $username = $request->get('username');
            $password = $request->get('password');

            // TODO find user
            $user = $em->getRepository(User::class)->find(['username' => $username, 'password' => $password]);

            if ($user) {
                // TODO auth user

                return $this->redirectToRoute('backend', [], 302);
            }
        }

        return $this->render('auth/sign-in.html.twig');
    }

    #[Route('/backend', name: 'backend')]
    public function backend(): Response
    {
        return $this->render('auth/backend.html.twig');
    }
}
