<?php
namespace OptimusCrime;

use OptimusCrime\Helpers\PostParser;

class Post
{
    const FULL = 1;
    const SHORT = 0;

    private $id;
    private $published;

    private $title;
    private $intro;
    private $description;

    private $posted;
    private $edited;

    private $shortContent;
    private $fullContent;

    public function __construct()
    {
        $this->published = true;
        $this->shortContent = '';
        $this->fullContent = '';
        $this->description = null;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setDescription($descrption)
    {
        $this->description = $description;
    }

    public function setPublished($flag)
    {
        $this->published = $flag;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setIntro($intro)
    {
        $this->intro = $intro;
    }

    public function setPosted($date)
    {
        $this->posted = $date;
    }

    public function setEdited($date)
    {
        $this->edited = $date;
    }

    public function setShortContent($content)
    {
        $this->shortContent = $content;
    }

    public function setFullContent($content)
    {
        $this->fullContent = $content;
    }

    public function addShortContent($content)
    {
        $this->shortContent .= $content;
    }

    public function addFullContent($content)
    {
        $this->fullContent .= $content;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDescription() 
    {
        return $this->description;
    }

    public function isPublished()
    {
        return $this->published;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getIntro()
    {
        return $this->intro;
    }

    public function getPosted()
    {
        return $this->posted;
    }

    public function getEdited()
    {
        return $this->edited;
    }

    public function getContent($mode = self::FULL)
    {
        if ($mode == self::FULL) {
            if ($this->fullContent === '') {
                return $this->shortContent;
            }

            return $this->fullContent;
        }

        return $this->shortContent;
    }

    public function getAlias()
    {
        return PostParser::titleToAlias($this->title);
    }

    public function parseMarkdown()
    {
        $markdownParser = new \cebe\markdown\GithubMarkdown();

        if ($this->intro !== null and strlen($this->intro) > 0) {
            $this->intro = $markdownParser->parse($this->intro);
        }

        if (strlen($this->shortContent) > 0) {
            $this->shortContent = $markdownParser->parse($this->shortContent);
        }

        if (strlen($this->fullContent) > 0) {
            $this->fullContent = $markdownParser->parse($this->fullContent);
        }
    }
}
