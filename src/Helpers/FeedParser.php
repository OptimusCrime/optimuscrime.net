<?php
namespace OptimusCrime\Helpers;

class FeedParser
{
    const POST_FILE_EXPRESSION = '/(?P<id>\d+)\.md$/';

    public function __construct()
    {
    }

    public static function getFeed($basePath)
    {
        $postsPath = $basePath . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR;
        if (file_exists($postsPath) === false) {
            return null;
        }

        return self::getPosts($postsPath);
    }

    private static function getPosts($path)
    {
        $allFiles = scandir($path);
        if (!is_array($allFiles)) {
            return null;
        }

        return self::filterFiles($allFiles);
    }

    private static function filterFiles(array $files)
    {
        $postFiles = [];
        foreach ($files as $file) {
            preg_match_all(self::POST_FILE_EXPRESSION, $file, $matches, PREG_SET_ORDER, 0);

            // Make sure we have any results at all
            if (empty($matches)) {
                continue;
            }

            // The match array is constructed as [0]['id'] for matched groups. Make sure we have these values in
            // the array.
            if (!isset($matches[0]) or (isset($matches[0]) and !isset($matches[0]['id']))) {
                continue;
            }

            // If the matched group has a length of zero, something went wrong with naming the files. We require a
            // numeric filename.
            if (strlen($matches[0]['id']) === 0) {
                continue;
            }

            $postFiles[] = intval($matches[0]['id']);
        }

        // Sort the files
        rsort($postFiles);

        return $postFiles;
    }
}
