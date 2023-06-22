<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
//use App\Form\Type\RegistrationType;
use App\Security\LoginFormAuthenticator;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
//use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @var UserService
     */
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @param Request                     $request           HTTP request
     * @param UserPasswordHasherInterface $passwordHasher    Password hasher
     * @param UserAuthenticatorInterface  $userAuthenticator Authenticator
     * @param LoginFormAuthenticator      $authenticator     Login form authenticator
     * @param EntityManagerInterface      $entityManager     Entity manager
     */
    #[Route('/signup', methods: ['GET', 'POST'], name: 'registration_signup')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        LoginFormAuthenticator $authenticator,
        EntityManagerInterface $entityManager
    ): Response {

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData(),
                )
            );

            $user->setRoles(['ROLE_USER']);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'message.registered_successfully');

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request,
            );
        }

        return $this->render(
            'registration/signup.html.twig',
            ['form' => $form->createView()]
        );
    }
}
