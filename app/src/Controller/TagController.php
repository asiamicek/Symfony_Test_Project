<?php
/**
 * Tag controller.
 */

namespace App\Controller;

use App\Entity\Tag;
use App\Service\TagServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class TagController.
 */
#[Route('/tag')]
class TagController extends AbstractController
{
    /**
     * Tag service.
     */
    private TagServiceInterface $tagService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * TagController constructor.
     *
     * @param TagServiceInterface $tagService Tag service interface
     * @param TranslatorInterface $translator Translator interface
     */
    public function __construct(TagServiceInterface $tagService, TranslatorInterface $translator)
    {
        $this->tagService = $tagService;
        $this->translator = $translator;
    }

    /**
     * Index action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route(
        '/',
        name: 'tag_index',
        methods: 'GET'
    )]
    public function index(Request $request): Response
    {
        $pagination = $this->tagService->getPaginatedList(
            $request->query->getInt('page', 1),
            $this->getUser()
        );

        return $this->render(
            'tag/index.html.twig',
            ['pagination' => $pagination]
        );
    }
}
