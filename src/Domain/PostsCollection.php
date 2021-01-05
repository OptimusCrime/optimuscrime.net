<?php
namespace OptimusCrime\Site\Domain;

class PostsCollection
{
    /** @var Post[] */
    private array $posts;

    /**
     * PostsCollection constructor.
     * @param Post[] $posts
     */
    private function __construct(array $posts)
    {
        $this->posts = $posts;
    }

    /**
     * @return Post[]
     */
    public function getPosts(): array
    {
        return $this->posts;
    }

    /**
     * @param Post[] $posts
     * @return PostsCollection
     */
    public static function fromArray(array $posts): PostsCollection
    {
        return new PostsCollection($posts);
    }
}
