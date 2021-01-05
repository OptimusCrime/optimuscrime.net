<?php
namespace OptimusCrime\Site\Domain\Builders;

use OptimusCrime\Site\Domain\Post;
use OptimusCrime\Site\Exceptions\IncompletePostFileException;

class PostBuilder
{
    private ?int $id;
    private ?string $alias;
    private bool $published;

    private ?string $title;
    private ?string $intro;
    private ?string $description;

    private ?string $posted;
    private ?string $edited;

    private ?string $content;

    public function __construct()
    {
        $this->id = null;
        $this->alias = null;
        $this->published = true;

        $this->title = null;
        $this->intro = null;
        $this->description = null;

        $this->posted = null;
        $this->edited = null;

        $this->content = null;
    }

    public function withId(int $id): PostBuilder
    {
        $this->id = $id;
        return $this;
    }

    public function withAlias(string $alias): PostBuilder
    {
        $this->alias = $alias;
        return $this;
    }

    public function withPublished(bool $published): PostBuilder
    {
        $this->published = $published;
        return $this;
    }

    public function withTitle(string $title): PostBuilder
    {
        $this->title = $title;
        return $this;
    }

    public function withIntro(string $intro): PostBuilder
    {
        $this->intro = $intro;
        return $this;
    }

    public function withDescription(string $description): PostBuilder
    {
        $this->description = $description;
        return $this;
    }

    public function withPosted(string $posted): PostBuilder
    {
        $this->posted = $posted;
        return $this;
    }

    public function withEdited(string $edited): PostBuilder
    {
        $this->edited = $edited;
        return $this;
    }

    public function withContent(string $content): PostBuilder
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return Post
     * @throws IncompletePostFileException
     */
    public function build(): Post
    {
        $this->validate();

        return new Post(
            $this->id,
            $this->alias,
            $this->published,

            $this->title,
            $this->intro,
            $this->description,

            $this->posted,
            $this->edited,

            $this->content
        );
    }

    /**
     * @throws IncompletePostFileException
     */
    private function validate(): void
    {
        $missingFields = [];

        if ($this->id === null) {
            $missingFields[] = 'id';
        }
        if ($this->alias === null) {
            $missingFields[] = 'alias';
        }
        if ($this->title === null) {
            $missingFields[] = 'title';
        }
        if ($this->posted === null) {
            $missingFields[] = 'posted';
        }

        if (count($missingFields) > 0) {
            throw new IncompletePostFileException('Missing fields for post: ' . implode(', ', $missingFields));
        }
    }
}
