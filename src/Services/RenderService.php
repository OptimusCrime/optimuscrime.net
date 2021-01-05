<?php
namespace OptimusCrime\Site\Services;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

use OptimusCrime\Site\Domain\Post;
use OptimusCrime\Site\Exceptions\PostNotFoundException;

class RenderService
{
    private PostsService $postService;
    private TwigService $twigService;

    /**
     * RenderService constructor.
     * @param PostsService $postService
     * @param TwigService $twigService
     */
    public function __construct(PostsService $postService, TwigService $twigService)
    {
        $this->postService = $postService;
        $this->twigService = $twigService;
    }

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function renderFrontpage(): string
    {
        return $this->twigService->render('listing.html', [
            'class' => 'frontpage',
            'posts' => $this->postService->getCollection(),
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function renderPostById(int $id): string
    {
        try {
            $post = $this->postService->getPost($id);

            return $this->renderPost($post);
        }
        catch (PostNotFoundException $_) {
            return $this->render404();
        }
    }

    /**
     * @param Post $post
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws PostNotFoundException
     */
    public function renderPost(Post $post): string
    {
        if (!$post->isPublished()) {
            throw new PostNotFoundException();
        }
        return $this->twigService->render('listing.html', [
            'class' => 'post',
            'posts' => [
                $post
            ],
            'title' => $post->getTitle() . ' :: OptimusCrime.net',
            'description' => $post->getDescription()
        ]);
    }

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render404(): string
    {
        return $this->twigService->render('404.html');
    }
}
