<?php
namespace OptimusCrime\Site\Local;

use OptimusCrime\Site\Services\PostsService;
use OptimusCrime\Site\Services\TwigService;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

use OptimusCrime\Site\Configuration\Configuration;
use OptimusCrime\Site\Exceptions\FileNotFoundException;
use OptimusCrime\Site\Exceptions\IncompletePostFileException;
use OptimusCrime\Site\Services\RenderService;

class Server
{
    private const POST_URL_PATTERN = '/\/(?P<id>\d+)\-(?:[a-z0-9_-]+)/';

    private string $rootDir;
    private string $url;

    private RenderService $renderService;

    /**
     * Server constructor.
     * @param string $rootDir
     * @throws FileNotFoundException
     * @throws IncompletePostFileException
     */
    public function __construct(string $rootDir)
    {
        $this->rootDir = $rootDir;
        $this->url = parse_url($_SERVER['REQUEST_URI'])['path'];

        $postsService = new PostsService($rootDir);
        $twigService = new TwigService($rootDir, Configuration::SERVER);

        $this->renderService = new RenderService($postsService, $twigService);
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function run(): void
    {
        if (str_starts_with($this->url, '/assets')) {
            $this->handleAssets();
            die();
        }

        if (str_starts_with($this->url, '/static')) {
            $this->handleStatic();
            die();
        }

        if ($this->url === '/') {
            echo $this->renderService->renderFrontpage();
            die();
        }

        preg_match_all(static::POST_URL_PATTERN, $this->url, $matches, PREG_SET_ORDER);
        if (isset($matches[0]) && isset($matches[0]['id'])) {
            echo $this->renderService->renderPostById(intval($matches[0]['id']));
            die();
        }

        $this->pageNotFoundHeader();
        echo $this->renderService->render404();
        die();
    }

    private function handleAssets(): void
    {
        $file = @file_get_contents($this->rootDir . $this->url);

        if ($file === false) {
            $this->pageNotFoundHeader();
            return;
        }

        if (str_ends_with($this->url, '.css')) {
            header('Content-Type: text/css');
        }

        echo $file;
    }

    private function handleStatic(): void
    {
        $staticPath = $this->rootDir
            . DIRECTORY_SEPARATOR
            . PostsService::POSTS_DIRECTORY
            . $this->url;

        if (!is_file($staticPath)) {
            $this->pageNotFoundHeader();

            return;
        }

        $info = pathinfo($staticPath);
        if (in_array($info['extension'], ['png', 'jpg', 'jpeg'])) {
            $this->handleStaticImage($staticPath, $info['extension']);
            return;
        }

        // Last option, just output the raw file
        header('Content-Type: text/plain');
        echo file_get_contents($staticPath);
    }

    private function handleStaticImage(string $staticPath, string $extension): void
    {
        switch ($extension) {
            case 'png':
                header('Content-Type: image/png');
                $im = \imagecreatefrompng($staticPath);
                \imagepng($im);
                die();
            default:
                header('Content-Type: image/jpeg');
                $im = \imagecreatefromjpeg($staticPath);
                \imagejpeg($im);
                die();
        }
    }

    private function pageNotFoundHeader(): void
    {
        header("HTTP/1.1 404 Not Found");
    }
}
