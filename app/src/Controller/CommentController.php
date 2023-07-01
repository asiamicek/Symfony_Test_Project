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

        $form = $this->createForm(FormType::class, $comment, [
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
