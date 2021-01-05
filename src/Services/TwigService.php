<?php
namespace OptimusCrime\Site\Services;

use OptimusCrime\Site\Configuration\Configuration;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class TwigService
{
    private const URL_IDENTIFIER_POST = 'post';

    private const DEFAULT_TITLE = 'OptimusCrime.net';
    private const DEFAULT_DESCRIPTION = 'A blog about computers and programming.';

    private string $mode;

    private Environment $twig;

    public function __construct(string $rootDir, string $mode)
    {
        $this->mode = $mode;

        $this->twig = new Environment(
            new FilesystemLoader(
                $rootDir . '/templates/'
            )
        );

        $this->registerFunctions();
    }

    /**
     * @param string $template
     * @param array $context
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render(string $template, array $context = []): string
    {
        return $this->twig->render(
            $template,
            array_merge(
                $context,
                [
                    'title' => $context['title'] ?? static::DEFAULT_TITLE,
                    'description' => $context['description'] ?? static::DEFAULT_DESCRIPTION,
                    'google_analytics' => $this->mode === Configuration::GENERATOR,
                    'google_analytics_code' => 'UA-43864641-1'
                ]
            )
        );
    }

    public function urlFor(string $identifier, array $context = []): string
    {
        switch ($identifier) {
            case static::URL_IDENTIFIER_POST:
                return str_replace(
                    [
                        '%id',
                        '%alias'
                    ],
                    [
                        $context['id'],
                        $context['alias']
                    ],
                    '/%id-%alias'
                );
        }

        return '';
    }

    private function registerFunctions(): void
    {
        $baseUrlFunction = new TwigFunction('base_url', function () {
            if (php_sapi_name() === 'cli-server') {
                return 'http://optimuscrime.local:8080';
            }

            return 'https://optimuscrime.net';
        });

        $urlFor = new TwigFunction('url_for', function (string $identifier, array $context = []) {
            return $this->urlFor($identifier, $context);
        });

        $this->twig->addFunction($baseUrlFunction);
        $this->twig->addFunction($urlFor);
    }
}
