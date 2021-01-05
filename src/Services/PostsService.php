<?php
namespace OptimusCrime\Site\Services;

use OptimusCrime\Site\Domain\Post;
use OptimusCrime\Site\Domain\PostsCollection;
use OptimusCrime\Site\Exceptions\FileNotFoundException;
use OptimusCrime\Site\Exceptions\IncompletePostFileException;
use OptimusCrime\Site\Exceptions\PostNotFoundException;

class PostsService
{
    const POSTS_DIRECTORY = 'posts';

    private const POST_FILE_EXPRESSION = '/(?P<id>\d+)\.md$/';

    private string $rootDir;

    private PostParserService $postParserService;
    private PostsCollection $postsCollection;

    /**
     * PostsService constructor.
     * @param string $rootDir
     * @throws FileNotFoundException|IncompletePostFileException
     */
    public function __construct(string $rootDir)
    {
        $this->rootDir = $rootDir;

        $this->postParserService = new PostParserService($rootDir);
        $this->postsCollection = $this->getPosts();
    }

    /**
     * @return Post[]
     */
    public function getCollection(): array
    {
        return $this->postsCollection->getPosts();
    }

    /**
     * @param int $id
     * @return Post
     * @throws PostNotFoundException
     */
    public function getPost(int $id): Post
    {
        foreach ($this->postsCollection->getPosts() as $post) {
            if ($post->getId() === $id && $post->isPublished()) {
                return $post;
            }
        }

        throw new PostNotFoundException();
    }

    /**
     * @return PostsCollection
     * @throws FileNotFoundException|IncompletePostFileException
     */
    private function getPosts(): PostsCollection
    {
        $postIds = $this->getPostFiles();

        /** @var Post[] $posts */
        $posts = [];

        foreach ($postIds as $postId) {
            $posts[] = $this->postParserService->parse($postId);
        }

        return PostsCollection::fromArray($posts);
    }

    /**
     * @return string[]
     */
    private function getPostFiles(): array
    {
        /** @var string[] $allFiles */
        $allFiles = scandir($this->rootDir . DIRECTORY_SEPARATOR . static::POSTS_DIRECTORY);

        /** @var string[] $postFiles */
        $postFiles = [];

        foreach ($allFiles as $file) {
            preg_match_all(static::POST_FILE_EXPRESSION, $file, $matches, PREG_SET_ORDER);

            if (empty($matches)) {
                continue;
            }

            if (isset($matches[0]) && isset($matches[0]['id']) && mb_strlen($matches[0]['id']) > 0) {
                $postFiles[] = $matches[0]['id'];
            }
        }

        // Newest -> oldest (sort in place, shrug)
        rsort($postFiles);

        return $postFiles;
    }
}
