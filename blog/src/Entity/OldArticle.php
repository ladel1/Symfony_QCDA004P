<?php 

namespace App\Entity;

use DateTimeInterface;

class OldArticle{

    public function __construct(
        private string $title,
        private string $content,
        private string $author,
        private DateTimeInterface $dateCreated,
        private bool $isPublished,
        private string $thumbnail
    )
    {
        
    }

    function setTitle($title) { $this->title = $title; }
    function getTitle() { return $this->title; }
    function setContent($content) { $this->content = $content; }
    function getContent() { return $this->content; }
    function setAuthor($author) { $this->author = $author; }
    function getAuthor() { return $this->author; }
    function setDateCreated($dateCreated) { $this->dateCreated = $dateCreated; }
    function getDateCreated() { return $this->dateCreated; }
    function setIsPublished($isPublished) { $this->isPublished = $isPublished; }
    function getIsPublished() { return $this->isPublished; }
    function setThumbnail($thumbnail) { $this->thumbnail = $thumbnail; }
    function getThumbnail() { return $this->thumbnail; }
        


}