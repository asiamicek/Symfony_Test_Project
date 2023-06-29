<?php
/**
 * Comment controller.
 */

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Enum\UserRole;
use App\Entity\User;
use App\Form\Type\CommentType;
use App\Service\CommentService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class CommentController.
 */
#[Route('/comment')]
class CommentController extends AbstractController
{
    /**
     * Comment service.
     */
    private CommentService $commentService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * @param CommentService      $commentService Comment service
     * @param TranslatorInterface $translator     Translator
     * @param Security            $security       Security
     */
    public function __construct(CommentService $commentService, TranslatorInterface $translator, Security $security)
    {
        $this->commentService = $commentService;
        $this->translator = $translator;
        $this->security = $security;
    }

    /**
     * Index action.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP response
     */
    #[Route(name: 'comment_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $pagination = $this->commentService->getPaginatedList(
            $request->query->getInt('page', 1)
        );

        return $this->render('comment/index.html.twig', ['pagination' => $pagination]);
    }

    //    /**
    //     * Show action.
    //     *
    //     * @param Comment $comment Comment entity
    //     *
    //     * @return Response HTTP response
    //     */
    //    #[Route("/{id}", name: "comment_show", methods: ["GET"],  requirements: ["id" => "[1-9]\d*"])]
    // //    #[IsGranted('VIEW', subject: 'comment')]
    //    public function show(Comment $comment): Response
    //    {
    //        return $this->render(
    //            'comment/show.html.twig',
    //            ['comment' => $comment]
    //        );
    //    }

    //    /**
    //     * Create action.
    //     *
    //     * @param Request $request HTTP request
    //     *
    //     * @return Response HTTP response
    //     *
    //     */
    //    #[Route("/create", methods: ["GET", "POST"], name: "comment_create")]
    //    public function create(Request $request): Response
    //    {
    //
    //        /** @var User $user */
    //        $user = $this->getUser();
    //        $comment = new Comment();
    //        $comment->setAuthor($user);
    //
    //        $form = $this->createForm(
    //            CommentType::class,
    //            $comment,
    //            ['action' => $this->generateUrl('comment_create')]
    //        );
    //        $form->handleRequest($request);
    //
    //        if ($form->isSubmitted() && $form->isValid()) {
    //            $this->commentService->save($comment);
    //
    //            $this->addFlash(
    //                'success',
    //                $this->translator->trans('message_created_successfully')
    //            );
    //
    //            return $this->redirectToRoute('post_index');
    //        }
    //
    //        return $this->render(
    //            'comment/create.html.twig',
    //            ['form' => $form->createView(),
    //        ]);
    //    }
    //    /**
    //     * Submit action.
    //     *
    //     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
    //     * @param \App\Entity\Comment                          $comment    Comment entity
    //     *
    //     * @return \Symfony\Component\HttpFoundation\Response HTTP response
    //     *
    //     * @throws \Doctrine\ORM\ORMException
    //     * @throws \Doctrine\ORM\OptimisticLockException
    //     *
    //     */
    //    #[Route("/{id}/submit", methods: ["GET", "PUT"], requirements: ["id" => "[1-9]\d*"], name: "comment_submit")]
    //    public function submit(Request $request, Comment $comment): Response
    //    {
    //
    //        if ($this->isGranted('ROLE_ADMIN')) {
    //            $form = $this->createForm(FormType::class, $comment, ['method' => 'PUT']);
    //            $form->handleRequest($request);
    //
    //            if ($form->isSubmitted() && $form->isValid()) {
    //                $this->commentService->save($comment);
    // //            $this->addFlash('success', 'message_updated_successfully');
    //
    //                return $this->redirectToRoute('comment_index');
    //            }
    //
    //            return $this->render(
    //                'comment/submit.html.twig',
    //                [
    //                    'form' => $form->createView(),
    //                    'comment' => $comment,
    //                ]
    //            );
    //        }
    //        else{
    //            $this->addFlash('warning', 'message_item_not_found');
    //            return $this->redirectToRoute('index');
    //        }
    //
    //
    //    }
    //
    //    /**
    //     * Edit action.
    //     *
    //     * @param Request  $request  HTTP request
    //     * @param Comment $comment Comment entity
    //     *
    //     * @return Response HTTP response
    //     */
    //    #[Route('/{id}/edit', name: 'post_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    //    public function edit(Request $request, Comment $comment): Response
    //    {
    //        $form = $this->createForm(
    //            CommentType::class,
    //            $comment,
    //            [
    //                'method' => 'PUT',
    //                'action' => $this->generateUrl('comment_edit', ['id' => $comment->getId()]),
    //            ]
    //        );
    //        $form->handleRequest($request);
    //
    //        if ($form->isSubmitted() && $form->isValid()) {
    //            $this->commentService->save($comment);
    //
    //            $this->addFlash(
    //                'success',
    //                $this->translator->trans('message.created_successfully')
    //            );
    //
    //            return $this->redirectToRoute('post_index');
    //        }
    //
    //        return $this->render(
    //            'comment/edit.html.twig',
    //            [
    //                'form' => $form->createView(),
    //                'comment' => $comment,
    //            ]
    //        );
    //    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Comment $comment Comment entity
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    #[Route('/{id}/delete', methods: ['GET', 'DELETE'], requirements: ['id' => "[1-9]\d*"], name: 'comment_delete')]
    public function deleteComment(Request $request, Comment $comment): Response
    {
        $user = $this->security->getUser();
        if ($comment->getAuthor() !== $this->getUser() and !in_array(UserRole::ROLE_ADMIN->value, $user->getRoles())) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message_action_impossible')
            );

            return $this->redirectToRoute('post_index');
        }

        $form = $this->createForm(CommentType::class, $comment, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('comment_delete', ['id' => $comment->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commentService->delete($comment);
            $this->addFlash('success', $this->translator->trans('message.deleted_successfully'));

            return $this->redirectToRoute('post_index');
        }

        return $this->render(
            'comment/delete.html.twig',
            [
                'form' => $form->createView(),
                'comment' => $comment,
            ]
        );
    }
}
