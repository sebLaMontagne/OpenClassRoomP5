<?php

class Article
{
    //ajouter une illustration + traducteur

    private $_id;
    private $_title;
    private $_lang;
    private $_content;
    private $_image;
    private $_category;
    private $_author;
    private $_likes;
    private $_dislikes;
    private $_comments;

    public function Id()        { return $this->_id; }
    public function Title()     { return $this->_title; }
    public function Lang()      { return $this->_lang; }
    public function Content()   { return $this->_content; }
    public function Image()     { return $this->_image; }
    public function Category()  { return $this->_category; }
    public function Author()    { return $this->_author; }
    public function Likes()     { return $this->_likes; }
    public function Dislikes()  { return $this->_dislikes; }
    public function Comments()  { return $this->_comments; }

    private function setId($id)
    {
        if(preg_match('@[0-9]+@', $id))
        {
            $this->_id = $id;
        }
    }
    private function setTitle($title)
    {
        $this->_title = htmlspecialchars($title);
    }
    private function setLang($lang)
    {
        $this->_lang = htmlspecialchars($lang);
    }
    private function setContent($content)
    {
        $this->_content = $content;
    }
    private function setImage($image)
    {
        if(preg_match('@^[0-9a-f]{13}.(png|tiff|gif|jpg|jpeg)$@', $image))
        {
            $this->_image = $image;
        }
    }
    private function setCategory(Category $category)
    {
        $this->_category = $category;
    }
    private function setAuthor(User $author)
    {
        $this->_author = $author;
    }
    private function setLikes($likes)
    {
        if(preg_match('@^[0-9]+$@', $likes))
        {
            $this->_likes = $likes;
        }
    }
    private function setDislikes($dislikes)
    {
        if(preg_match('@^[0-9]+$@', $dislikes))
        {
            $this->_dislikes = $dislikes;
        }
    }
    
    //demander un array en parametre + faire un foreach en vérifiant le type de chaque entrée du tableau
    private function setComments($comments)
    {
        $this->_comments = $comments;
    }

    private function hydrate(array $data)
    {
        foreach($data as $key => $value)
        {
            $method = 'set'.ucfirst($key);
            if(method_exists($this, $method))
            {
                $this->$method($value);
            }
        }
    }

    public function __construct($data)
    {
        $this->hydrate($data);
    }
}