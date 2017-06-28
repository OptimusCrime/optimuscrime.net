<?php
namespace OptimusCrime\Helpers;

use OptimusCrime\Post;

class PostParser
{
    const TITLE = 0;
    const FULL = 1;

    private $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function postExists($id)
    {
        if (!is_numeric($id)) {
            return false;
        }

        return file_exists($this->createPath($id));
    }

    private function createPath($id)
    {
        return $this->path . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . $id . '.md';
    }

    public function titleToAlias($title)
    {
        // All lowercase
        $title = strtolower($title);

        // Remove everything except alphanumeric, +, -, and ...?
        // TODO
        return $title;
    }

    public function getPost($id, $mode = self::FULL)
    {
        $file = new \SplFileObject($this->createPath($id));

        $post = new Post();
        $post->setId($id);

        $lines = 0;
        $readMore = false;

        while (!$file->eof()) {
            $lines++;
            $line = $file->fgets();

            if (substr($line, 0, 3) === '|||') {
                self::parsePostMeta($post, substr($line, 3));

                if ($mode == self::TITLE and $post->getTitle() !== null) {
                    return $post;
                }

                continue;
            }

            if (substr($line, 0, 1) === '^') {
                $post->setIntro(substr($line, 1));
                continue;
            }

            if (substr($line, 0, 7) === '[more]') {
                $readMore = true;
                continue;
            }

            if ($readMore) {
                $post->addFullContent($line);
                continue;
            }

            $post->addShortContent($line);
        }

        if ($lines === 0) {
            return null;
        }

        $post->parseMarkdown();

        return $post;
    }

    private static function parsePostMeta(Post $post, $line)
    {
        $lineSplit = explode(':', $line);
        if (!in_array($lineSplit[0], ['Published', 'Title', 'Posted', 'Edited'])) {
            return;
        }

        $value = trim($lineSplit[1]);
        if ($value === null or strlen($value) === 0) {
            return;
        }

        if ($lineSplit[0] === 'Published') {
            if (strtolower($value) === 'no') {
                $post->setPublished(false);
                return;
            }
        }

        if ($lineSplit[0] === 'Posted') {
            $post->setPosted($value);
            return;
        }

        if ($lineSplit[0] === 'Edited') {
            $post->setEdited($value);
            return;
        }

        if ($lineSplit[0] === 'Title') {
            $post->setTitle($value);
            return;
        }
    }
}
