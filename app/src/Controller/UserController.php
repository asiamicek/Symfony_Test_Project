<?php
/**
 * User controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\UserPasswordType;
use App\Form\Type\UserdataType;
use App\Service\UserService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class UserController.
 *
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * User service.
     */
    private UserService $userService;

    /**
     * UserController constructor.
     *
     * @param UserService         $userService User service
     * @param TranslatorInterface $translator  Translator interface
     */
    public function __construct(UserService $userService, TranslatorInterface $translator)
    {
        $this->userService = $userService;
        $this->translator = $translator;
    }

    /**
     * Index action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="user_index",
     * )
     */
    public function index(Request $request): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('warning', $this->translator->trans('message_action_impossible'));

            return $this->redirectToRoute('index');
        }

        $page = $request->query->getInt('page', 1);
        $pagination = $this->userService->createPaginatedList($page);

        return $this->render(
            'user/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param User $user User entity
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="user_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(User $user): Response
    {
        $log = $this->getUser();
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->render(
                'user/show.html.twig',
                ['user' => $user]
            );
        }
        if ($user === $log) {
            return $this->render(
                'user/show.html.twig',
                ['user' => $log]
            );
        }
        if ($user !== $log) {
            $this->addFlash('warning', $this->translator->trans('message_action_impossible'));

            return $this->redirectToRoute('index');
        }

        return true;
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param User    $user    User
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws \Symfony\Component\Form\Exception\LogicException
     * @throws \Symfony\Component\Form\Exception\OutOfBoundsException
     * @throws \Symfony\Component\Form\Exception\RuntimeException
     */
    #[Route(
        path: '/{id}/edit',
        name: 'user_edit',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET', 'PUT'],
    )]
    public function edit(Request $request, User $user): Response
    {
        $loggedInUser = $this->getUser();

        // Check if the logged-in user is not null and has the necessary permissions
        if (!$this->isGranted('ROLE_ADMIN') && $loggedInUser !== $user) {
            // Handle the case when the user is not authorized to edit this user
            // Redirect or show an error message
            // For example:
            $this->addFlash(
                'warning',
                $this->translator->trans('message_action_impossible')
            );

            return $this->redirectToRoute('index');
        }

        $form = $this->createForm(UserdataType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->save($user);
            $this->addFlash('success', $this->translator->trans('message_updated_successfully'));

            return $this->redirectToRoute('index');
        }

        return $this->render(
            'user/edit.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }

    /**
     * Edit password action.
     *
     * @param Request                     $request        HTTP request
     * @param User                        $user           User entity
     * @param UserPasswordHasherInterface $passwordHasher User Password Hasher Interface
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}/edit-password',
        name: 'user_edit_password',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|PUT',
    )]
    public function editPassword(Request $request, User $user, UserPasswordHasherInterface $passwordHasher): Response
    {
        $loggedInUser = $this->getUser();
        // Check if the logged-in user is not null and has the necessary permissions
        if (!$this->isGranted('ROLE_ADMIN') && $loggedInUser !== $user) {
            // Handle the case when the user is not authorized to edit this user
            // Redirect or show an error message
            // For example:
            $this->addFlash(
                'warning',
                $this->translator->trans('message_action_impossible')
            );

            return $this->redirectToRoute('index');
        }

        $form = $this->createForm(
            UserPasswordType::class,
            $user,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl(
                    'user_edit_password',
                    ['id' => $user->getId()],
                ),
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('password')->getData();

            if ($newPassword) {
                // Set the new password only if a new password is provided
                $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
                $user->setPassword($hashedPassword);
            }

            $this->userService->save($user);
            $this->addFlash('success', $this->translator->trans('message_updated_successfully'));

            return $this->redirectToRoute('index');
        }

        return $this->render(
            'user/edit_password.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }
}
