<?php
namespace OptimusCrime\Site\Services;

use League\CommonMark\GithubFlavoredMarkdownConverter;

class MarkdownService
{
    private GithubFlavoredMarkdownConverter $githubFlavoredMarkdownConverter;

    public function __construct()
    {
        $this->githubFlavoredMarkdownConverter = new GithubFlavoredMarkdownConverter([
            'html_input' => 'escape',
            'allow_unsafe_links' => true,
        ]);
    }

    public function convert(string $content): string
    {
        return $this->githubFlavoredMarkdownConverter->convertToHtml($content);
    }
}
