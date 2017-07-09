<?php
namespace OptimusCrime\Helpers;

use OptimusCrime\Post;

class PostParser
{
    const CHARSET = 'UTF-8';

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

    public static function titleToAlias($title)
    {
        $title = strtolower($title);
        $title = str_replace('&nbsp;', ' ', $title);
        $title = html_entity_decode($title, ENT_QUOTES, self::CHARSET);
        $title = str_replace('&', 'and', $title);

        // Some weird handling of converting charset and such
        $title = iconv(mb_detect_encoding($title), self::CHARSET . '//TRANSLIT//IGNORE', $title);

        // This regex removed all non-url valid characters from the string
        $title = preg_replace('/[\0\x0B\t\n\r\f\a&=+%#<>"~`@\?\[\]\{\}\|\^\'\\\\]/', '', $title);

        // Replace spaces with a dash
        $title = preg_replace('/\s+/u', '-', $title);

        // Replace multiple dashes into a single dash

        // Convert to lowercase
        return mb_convert_case($title, MB_CASE_LOWER, self::CHARSET);
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
        if (count($lineSplit) > 2) {
            $lineSplit = self::fixPostMetaSplit($lineSplit);
        }

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

    private static function fixPostMetaSplit(array $split)
    {
        $newSplit = [];
        $newSplit[] = $split[0];
        unset($split[0]);

        $newSplit[] = implode(':', $split);

        return $newSplit;
    }
}
