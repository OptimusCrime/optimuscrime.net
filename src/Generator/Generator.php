<?php
namespace OptimusCrime\Site\Generator;

use OptimusCrime\Site\Configuration\Configuration;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

use OptimusCrime\Site\Exceptions\FileNotFoundException;
use OptimusCrime\Site\Exceptions\IncompletePostFileException;
use OptimusCrime\Site\Exceptions\GeneratorException;
use OptimusCrime\Site\Exceptions\PostNotFoundException;
use OptimusCrime\Site\Services\PostsService;
use OptimusCrime\Site\Services\TwigService;
use OptimusCrime\Site\Services\RenderService;

class Generator
{
    private const CONTENT_DIRECTORY = 'content';

    private string $rootDir;

    private RenderService $renderService;
    private PostsService $postsService;
    private TwigService $twigService;

    /**
     * Generator constructor.
     * @param string $rootDir
     * @throws FileNotFoundException
     * @throws IncompletePostFileException
     */
    public function __construct(string $rootDir)
    {
        $this->rootDir = $rootDir;

        $this->postsService = new PostsService($rootDir);
        $this->twigService = new TwigService($rootDir, Configuration::GENERATOR);

        $this->renderService = new RenderService($this->postsService, $this->twigService);
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws GeneratorException
     * @throws PostNotFoundException
     */
    public function run(): void
    {
        $this->createFrontpageFile();
        $this->createPostFiles();
        $this->create404File();
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws GeneratorException
     */
    private function createFrontpageFile(): void
    {
        $filePath = $this->contentPath() . DIRECTORY_SEPARATOR . 'index.html';
        $content = $this->renderService->renderFrontpage();

        static::createFile($filePath, $content);
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws GeneratorException
     * @throws PostNotFoundException
     */
    private function createPostFiles(): void
    {
        $posts = $this->postsService->getCollection();

        foreach ($posts as $post) {
            if (!$post->isPublished()) {
                continue;
            }

            $url = $this->twigService->urlFor(
                'post',
                [
                    'id' => $post->getId(),
                    'alias' => $post->getAlias()
                ]
            );

            $filePath = $this->contentPath() . $url . '.html';
            $content = $this->renderService->renderPost($post);

            static::createFile($filePath, $content);
        }
    }

    /**
     * @throws GeneratorException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private function create404File(): void
    {
        $filePath = $this->contentPath() . DIRECTORY_SEPARATOR . '404.html';
        $content = $this->renderService->render404();

        static::createFile($filePath, $content);
    }

    private function contentPath(): string
    {
        return $this->rootDir . DIRECTORY_SEPARATOR . static::CONTENT_DIRECTORY;
    }

    /**
     * @param string $filePath
     * @param string $content
     * @throws GeneratorException
     */
    private static function createFile(string $filePath, string $content): void
    {
        $result = @file_put_contents($filePath, $content);

        if ($result === false) {
            throw new GeneratorException('Failed to create file: ' . $filePath);
        }
    }
}
