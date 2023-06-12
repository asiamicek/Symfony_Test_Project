<?php
///**
// * Comment controller.
// */
//
//namespace App\Controller;
//
//use App\Entity\Comment;
////use App\Form\Type\CommentType;
////use App\Service\CommentServiceInterface;
//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
////use Symfony\Component\Form\Extension\Core\Type\FormType;
//use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Routing\Annotation\Route;
//
//
////use Symfony\Contracts\Translation\TranslatorInterface;
////use App\Entity\Category;
//
//
//
//
///**
// * Class CommentController.
// *
// * @Route("/comment")
// */
//class CommentController extends AbstractController
//{
//    /**
//     * Comment service.
//     */
//    private CommentServiceInterface $commentService;
//
//
//    /**
//     * Constructor.
//     *
//     * @param CommentServiceInterface $commentService Comment service
//     *
//     */
//    public function __construct(CommentServiceInterface $commentService)
//    {
//        $this->commentService = $commentService;
//    }
//
//    /**
//     * Index action.
//     *
//     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
//     *
//     * @return \Symfony\Component\HttpFoundation\Response HTTP response
//     *
//     * @Route(
//     *     "/",
//     *     methods={"GET"},
//     *     name="comment_index",
//     * )
//     */
//    public function index(Request $request): Response
//    {
//        if ($this->isGranted('ROLE_ADMIN')) {
//            $pagination = $this->commentService->getPaginatedList(
//                $request->query->getInt('page', 1)
//            );
//            return $this->render('comment/index.html.twig', ['pagination' => $pagination]);
//        }
//        else{
//            $this->addFlash('warning', 'message_item_not_found');
//            return $this->redirectToRoute('index');
//        }
//
//    }
//
//    /**
//     * Show action.
//     *
//     * @param \App\Entity\Comment $comment Comment entity
//     *
//     * @return \Symfony\Component\HttpFoundation\Response HTTP response
//     *
//     * @Route(
//     *     "/{id}",
//     *     methods={"GET"},
//     *     name="comment_show",
//     *     requirements={"id": "[1-9]\d*"},
//     * )
//     */
//    public function show(Comment $comment): Response
//    {
//        if ($this->isGranted('ROLE_ADMIN')) {
//            return $this->render('comment/show.html.twig', ['comment' => $comment]) ;
//        }
//        else{
//            $this->addFlash('warning', 'message_item_not_found');
//            return $this->redirectToRoute('index');
//        }
//
//    }
//
//    /**
//     * Create action.
//     *
//     * @param Request $request HTTP request
//     *
//     * @return Response HTTP response
//     *
//     * @Route(
//     *     "/create",
//     *     methods={"GET|POST"},
//     *     name="comment_create",
//     * )
//     *
//     */
//    public function create(Request $request): Response
//    {
//        $comment = new Comment();
//        $form = $this->createForm(CommentType::class, $comment, ['action' => $this->generateUrl('comment_create')]);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $this->commentService->save($comment);
//
//            return $this->redirectToRoute('index');
//        }
//
//        return $this->render('comment/create.html.twig', [
//            'form' => $form->createView(),
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
//     * @Route(
//     *     "/{id}/submit",
//     *     methods={"GET", "PUT"},
//     *     requirements={"id": "[1-9]\d*"},
//     *     name="comment_submit",
//     * )
//     */
//    public function submit(Request $request, Comment $comment): Response
//    {
//
//        if ($this->isGranted('ROLE_ADMIN')) {
//            $form = $this->createForm(FormType::class, $comment, ['method' => 'PUT']);
//            $form->handleRequest($request);
//
//            if ($form->isSubmitted() && $form->isValid()) {
//                $this->commentService->save($comment);
////            $this->addFlash('success', 'message_updated_successfully');
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
//    /**
//     * Delete action.
//     *
//     * @param Request  $request  HTTP request
//     * @param Comment $comment Comment entity
//     *
//     * @return Response HTTP response
//     *
//     * @Route(
//     *     "/{id}/delete",
//     *     methods={"GET", "DELETE"},
//     *     requirements={"id": "[1-9]\d*"},
//     *     name="comment_delete",
//     * )
//     */
//    public function delete(Request $request, Comment $comment): Response
//    {
//        if ($this->isGranted('ROLE_ADMIN')) {
//            $form = $this->createForm(FormType::class, $comment, [
//                'method' => 'DELETE',
//                'action' => $this->generateUrl('comment_delete', ['id' => $comment->getId()]),
//            ]);
//            $form->handleRequest($request);
//            if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
//                $form->submit($request->request->get($form->getName()));
//            }
//
//            if ($form->isSubmitted() && $form->isValid()) {
//                $this->commentService->delete($comment);
//
//
//                return $this->redirectToRoute('comment_index');
//            }
//
//            return $this->render(
//                'comment/delete.html.twig',
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
//}
