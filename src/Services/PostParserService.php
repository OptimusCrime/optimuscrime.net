<?php
namespace OptimusCrime\Site\Services;

use SplFileObject;

use OptimusCrime\Site\Domain\Builders\PostBuilder;
use OptimusCrime\Site\Domain\Post;
use OptimusCrime\Site\Exceptions\FileNotFoundException;
use OptimusCrime\Site\Exceptions\IncompletePostFileException;

class PostParserService
{
    private const CHARSET = 'UTF-8';

    private const POST_FILE_ENDING = 'md';
    private const METADATA_PREFIX = '|||';
    private const INTRO_PREFIX = '^';

    private const METADATA_PUBLISHED = 'published';
    private const METADATA_TITLE = 'title';
    private const METADATA_POSTED = 'posted';
    private const METADATA_EDITED = 'edited';
    private const METADATA_DESCRIPTION = 'description';

    private string $rootDir;
    private MarkdownService $markdownService;

    public function __construct(string $rootDir)
    {
        $this->rootDir = $rootDir;

        $this->markdownService = new MarkdownService();
    }

    /**
     * @param string $id
     * @return Post
     * @throws FileNotFoundException|IncompletePostFileException
     */
    public function parse(string $id): Post
    {
        $filePath = static::getFilePath($this->rootDir, $id);

        if (!file_exists($filePath)) {
            throw new FileNotFoundException('Could not find file: ' . $filePath);
        }

        return $this->parseFileToPost($id, $filePath);
    }

    /**
     * @param string $id
     * @param string $filePath
     * @return Post
     * @throws IncompletePostFileException
     */
    private function parseFileToPost(string $id, string $filePath): Post
    {
        $file = new SplFileObject($filePath);

        $lines = 0;
        $content = '';

        $postBuilder = (new PostBuilder())
            ->withId(intval($id));

        while (!$file->eof()) {
            $lines++;
            $line = $file->fgets();

            if (mb_substr($line, 0, 3) === static::METADATA_PREFIX) {
                $postBuilder = static::parseMetadata(
                    $postBuilder,
                    mb_substr($line, mb_strlen(static::METADATA_PREFIX))
                );

                continue;
            }

            if (str_starts_with($line, static::INTRO_PREFIX)) {
                $postBuilder = $postBuilder->withIntro(
                    $this->markdownService->convert(
                        mb_substr($line, mb_strlen(static::INTRO_PREFIX))
                    )
                );

                continue;
            }

            $content .= $line;
        }

        if (mb_strlen($content) > 0) {
            $postBuilder = $postBuilder->withContent(
                $this->markdownService->convert(
                    $content
                )
            );
        }

        if ($lines === 0) {
            throw new IncompletePostFileException('No lines found in file: ' . $filePath);
        }

        return $postBuilder->build();
    }

    private static function getFilePath(string $rootDir, string $id): string
    {
        return $rootDir
            . DIRECTORY_SEPARATOR
            . PostsService::POSTS_DIRECTORY
            . DIRECTORY_SEPARATOR
            . $id
            . '.'
            . static::POST_FILE_ENDING;
    }

    private static function parseMetadata(PostBuilder $postBuilder, $line): PostBuilder
    {
        /** @var string[] $metadataLineSplit */
        $metadataLineSplit = explode(':', $line);

        $metadata = trim(mb_strtolower($metadataLineSplit[0]));
        $value = static::fixMetadataSplit($metadataLineSplit);

        if ($value === null) {
            return $postBuilder;
        }

        $validMetadataTypes = [
            static::METADATA_PUBLISHED,
            static::METADATA_TITLE,
            static::METADATA_POSTED,
            static::METADATA_EDITED,
            static::METADATA_DESCRIPTION
        ];

        if (!in_array($metadata, $validMetadataTypes)) {
            return $postBuilder;
        }

        switch(true) {
            case $metadata === static::METADATA_PUBLISHED:
                return $postBuilder->withPublished(mb_strtolower($value) === 'yes');
            case $metadata === static::METADATA_TITLE:
                return $postBuilder
                    ->withTitle(trim($value))
                    ->withAlias(static::titleToUrl($value));
            case $metadata === static::METADATA_POSTED:
                return $postBuilder->withPosted($value);
            case $metadata === static::METADATA_EDITED:
                return $postBuilder->withEdited($value);
            case $metadata === static::METADATA_DESCRIPTION:
                return $postBuilder->withDescription($value);
            default:
                return $postBuilder;
        }
    }

    /**
     * @param string[] $metadataSplit
     * @return ?string
     */
    private static function fixMetadataSplit(array $metadataSplit): ?string
    {
        if (count($metadataSplit) === 2) {
            return $metadataSplit[1];
        }

        if (count($metadataSplit) < 2) {
            return null;
        }

        unset($metadataSplit[0]);

        return implode (':', $metadataSplit);
    }

    private static function titleToUrl(string $title): string
    {
        $title = trim(mb_strtolower($title));
        $title = str_replace('&nbsp;', ' ', $title);
        $title = html_entity_decode($title, ENT_QUOTES, static::CHARSET);
        $title = str_replace('&', 'and', $title);

        // Some weird handling of converting charset and such
        $title = iconv(mb_detect_encoding($title), static::CHARSET . '//TRANSLIT//IGNORE', $title);

        // Replace spaces with a dash
        $title = preg_replace('/\s+/u', '-', $title);

        // This regex removed all non-url valid characters from the string
        $title = preg_replace('/[^a-z0-9_-]/', '', $title);

        // Replace multiple dashes into a single dash
        $title = preg_replace('/-+/', '-', $title);

        // Convert to lowercase
        return mb_convert_case($title, MB_CASE_LOWER, static::CHARSET);
    }
}
