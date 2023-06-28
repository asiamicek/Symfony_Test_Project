<?php
/**
 * Index controller.
 */

namespace App\Controller;

use App\Service\PostService;
use App\Service\PostServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IndexController.
 *
 * @Route("/")
 */
class IndexController extends AbstractController
{
    /**
     * Post service.
     */
    private PostServiceInterface $postService;

    /**
     * Constructor.
     *
     * @param PostService $postService Post service
     */
    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * Index action.
     *
     * @return Response HTTP response
     */
    #[Route(name: 'index', methods: 'GET')]
    public function index(): Response
    {
        $posts = $this->postService->getAllPosts();

        return $this->render(
            'index.html.twig',
            ['posts' => $posts]
        );
    }
}
