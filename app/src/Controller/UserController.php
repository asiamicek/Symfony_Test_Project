<?php
/**
 * User controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\UserdataType;
use App\Repository\UserRepository;
use App\Service\UserService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserController.
 *
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * User service.
     *
     * @var \App\Service\UserService
     */
    private UserService $userService;

    /**
     * UserController constructor.
     *
     * @param \App\Service\UserService $userService User service
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
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
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(Request $request): Response
    {
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
            $this->addFlash('warning', 'message_item_not_found');

            return $this->redirectToRoute('post_index');
        }

        return true;
    }

    /**
     * Edit action.
     *
     * @param Request                      $request         HTTP request
     * @param User                         $user
     * @param UserPasswordHasherInterface  $passwordHasher
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws \Symfony\Component\Form\Exception\LogicException
     * @throws \Symfony\Component\Form\Exception\OutOfBoundsException
     * @throws \Symfony\Component\Form\Exception\RuntimeException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="user_edit",
     * )
     */
    public function edit(Request $request, User $user, UserPasswordHasherInterface $passwordHasher): Response
    {
        $loggedInUser = $this->getUser();

        // Check if the logged-in user is not null and has the necessary permissions
        if (!$this->isGranted('ROLE_ADMIN') && $loggedInUser !== $user) {
            // Handle the case when the user is not authorized to edit this user
            // Redirect or show an error message
            // For example:
            throw $this->createAccessDeniedException('You are not authorized to edit this user.');
        }

        $form = $this->createForm(UserdataType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('newPassword')->getData();

            if ($newPassword) {
                // Set the new password only if a new password is provided
                $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
                $user->setPassword($hashedPassword);
            }

            $this->userService->save($user);
            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('user_index');
        }

        return $this->render(
            'user/edit.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }


//    /**
//     * Edit action.
//     *
//     * @param Request                      $request         HTTP request
//     * @param User                         $user
//     * @param UserPasswordEncoderInterface $passwordEncoder
//     *
//     * @return Response HTTP response
//     *
//     * @throws ORMException
//     * @throws OptimisticLockException
//     * @throws \Symfony\Component\Form\Exception\LogicException
//     * @throws \Symfony\Component\Form\Exception\OutOfBoundsException
//     * @throws \Symfony\Component\Form\Exception\RuntimeException
//     *
//     * @Route(
//     *     "/{id}/edit",
//     *     methods={"GET", "PUT"},
//     *     requirements={"id": "[1-9]\d*"},
//     *     name="user_edit",
//     * )
//     */
//    public function edit(Request $request, User $user, UserPasswordHasherInterface $passwordHasher,): Response
//    {
//        $log = $this->getUser();
//        if ($this->isGranted('ROLE_ADMIN')) {
//            $form = $this->createForm(UserdataType::class, $user, ['method' => 'PUT']);
//            $form->handleRequest($request);
//
//            if ($form->isSubmitted() && $form->isValid()) {
//                $user->setPassword(
//                    $passwordHasher->hashPassword(
//                        $user,
//                        $form->get('newPassword')->getData()
//                    )
//                );
//                $this->userService->save($user);
//                $this->addFlash('success', 'message_updated_successfully');
//
//                return $this->redirectToRoute('user_index');
//            }
//
//            return $this->render(
//                'user/edit.html.twig',
//                [
//                    'form' => $form->createView(),
//                    'user' => $user,
//                ]
//            );
//        } else {
//            $form = $this->createForm(UserdataType::class, $log, ['method' => 'PUT']);
//            $form->handleRequest($request);
//
//            if ($form->isSubmitted() && $form->isValid()) {
////                $newPassword = $form->get('newPassword')->getData();
////                $userRepository->save($log, $newPassword);
//                $log = $this->getUser();
//                $user->setPassword(
//                    $passwordHasher->hashPassword(
//                        $log,
//                        $form->get('newPassword')->getData()
//                    )
//                );
//                $this->userService->save($user);
//
//                $this->addFlash('success', 'message_updated_successfully');
//
//                return $this->redirectToRoute('post_index');
//            }
//
//            return $this->render(
//                'user/edit.html.twig',
//                [
//                    'form' => $form->createView(),
//                    'user' => $log,
//                ]
//            );
//        }
//    }



}
