<?php
/**
 * Post controller.
 */

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Form\Type\CommentType;
use App\Form\Type\PostType;
use App\Service\CommentServiceInterface;
use App\Service\PostServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\Extension\Core\Type\FormType;

/**
 * Class PostController.
 */
#[Route('/post')]
class PostController extends AbstractController
{
    /**
     * Post service.
     */
    private PostServiceInterface $postService;

    /**
     * Comment service.
     */
    private CommentServiceInterface $commentService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Security.
     */
    private Security $security;

    /**
     * Constructor.
     *
     * @param PostServiceInterface    $postService    Post service
     * @param TranslatorInterface     $translator     Translator
     * @param CommentServiceInterface $commentService Comment service
     * @param Security                $security       Security
     */
    public function __construct(PostServiceInterface $postService, TranslatorInterface $translator, CommentServiceInterface $commentService, Security $security)
    {
        $this->postService = $postService;
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
    #[Route(name: 'post_index', methods: 'GET')]
    public function index(Request $request): Response
    {
        //        $user = $this->getUser();
        //        var_dump($user);

        $filters = $this->getFilters($request);
        $pagination = $this->postService->getPaginatedList(
            $request->query->getInt('page', 1),
            $filters
        );
        //        $this->getUser()
        return $this->render('post/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * @param Post    $post    Post entity
     * @param Request $request Request
     *
     * @return Response HTTP response
     */
    #[Route('/{id}', name: 'post_show', methods: ['GET', 'POST'], requirements: ['id' => "[1-9]\d*"])]
    public function show(Post $post, Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $comment = new Comment();
        $comment->setAuthor($user);

        $form = $this->createForm(CommentType::class, $comment, ['action' => $this->generateUrl('post_show', ['id' => $post->getId()])]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setPost($post);
            $this->commentService->save($comment);

            $this->addFlash(
                'success',
                $this->translator->trans('message_created_successfully')
            );

            return $this->redirectToRoute('post_show', ['id' => $post->getId()]);
            //            $this->categoryService->save($category);
        }

        $comments = $this->commentService->findByPost($post);

        return $this->render(
            'post/show.html.twig',
            ['post' => $post, 'form' => $form->createView(), 'comments' => $comments]
        );
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route('/create', methods: ['GET', 'POST'], name: 'post_create')]
    #[IsGranted('ROLE_USER')]
    public function create(Request $request): Response
    {
        //        if (!$this->isGranted('ROLE_USER')) {
        //            $this->addFlash('warning', $this->translator->trans('message_action_impossible'));
        //
        //            return $this->redirectToRoute('index');
        //        }

        /** @var User $user */
        $user = $this->getUser();
        $post = new Post();
        $post->setAuthor($user);
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->postService->save($post);

            $this->addFlash(
                'success',
                $this->translator->trans('message_created_successfully')
            );

            return $this->redirectToRoute('post_index');
        }

        return $this->render(
            'post/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Post    $post    Post entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/edit', name: 'post_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function edit(Request $request, Post $post): Response
    {
        $user = $this->security->getUser();
        if (!$this->isGranted('EDIT', $post)) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message_action_impossible')
            );

            return $this->redirectToRoute('post_index');
        }

        $form = $this->createForm(
            PostType::class,
            $post,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('post_edit', ['id' => $post->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->postService->save($post);

            $this->addFlash(
                'success',
                $this->translator->trans('message_edited_successfully')
            );

            return $this->redirectToRoute('post_index');
        }

        return $this->render(
            'post/edit.html.twig',
            [
                'form' => $form->createView(),
                'post' => $post,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Post    $post    Post entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'post_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    public function delete(Request $request, Post $post): Response
    {
        $user = $this->security->getUser();
        if (!$this->isGranted('DELETE', $post)) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message_action_impossible')
            );

            return $this->redirectToRoute('post_index');
        }

        $form = $this->createForm(FormType::class, $post, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('post_delete', ['id' => $post->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comments = $this->commentService->findByPost($post);
            foreach ($comments as $comment) {
                $this->commentService->delete($comment);
            }
            $this->postService->delete($post);

            $this->addFlash(
                'success',
                $this->translator->trans('message_deleted_successfully')
            );

            return $this->redirectToRoute('post_index');
        }

        return $this->render(
            'post/delete.html.twig',
            [
                'form' => $form->createView(),
                'post' => $post,
            ]
        );
    }

    /**
     * Get filters from request.
     *
     * @param Request $request HTTP request
     *
     * @return array<string, int> Array of filters
     *
     * @psalm-return array{category_id: int, tag_id: int, status_id: int}
     */
    private function getFilters(Request $request): array
    {
        $filters = [];
        $filters['category_id'] = $request->query->getInt('filters_category_id');
        $filters['tag_id'] = $request->query->getInt('filters_tags_id');

        return $filters;
    }
}
