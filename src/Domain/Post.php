<?php
namespace OptimusCrime\Site\Domain;

class Post
{
    private int $id;
    private string $alias;
    private bool $published;

    private string $title;
    private ?string $intro;
    private ?string $description;

    private string $posted;
    private ?string $edited;

    private string $content;

    public function __construct(
        int $id,
        string $alias,
        bool $published,

        string $title,
        ?string $intro,
        ?string $description,

        string $posted,
        ?string $edited,

        string $content
    )
    {
        $this->id = $id;
        $this->alias = $alias;
        $this->published = $published;

        $this->title = $title;
        $this->intro = $intro;
        $this->description = $description;

        $this->posted = $posted;
        $this->edited = $edited;

        $this->content = $content;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    public function isPublished(): bool
    {
        return $this->published;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getIntro(): ?string
    {
        return $this->intro;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getPosted(): string
    {
        return $this->posted;
    }

    public function getEdited(): ?string
    {
        return $this->edited;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
