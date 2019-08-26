<?php

class ArticleComment
{
    private $_id;
    private $_article_id;
    private $_author;
    private $_content;
    private $_isApproved;

    public function Id()        { return $this->_id; }
    public function ArticleId() { return $this->_article_id; }
    public function Author()    { return $this->_author; }
    public function Content()   { return $this->_content; }
    public function IsApproved(){ return $this->_isApproved; }

    private function setId($data)
    {
        if(preg_match('@^[0-9]+$@', $data))
        {
            $this->_id = $data;
        }
    }
    private function setArticle_id($data)
    {
        if(preg_match('@^[0-9]+$@', $data))
        {
            $this->_article_id = $data;
        }
    }
    private function setAuthor(User $data)
    {
        $this->_author = $data;
    }
    private function setContent($data)
    {
        $this->_content = $data;
    }
    private function setIsApproved($data)
    {
        if(preg_match('@^(0|1)$@', $data))
        {
            $this->_isApproved = $data;
        }
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
    public function __construct(array $data)
    {
        $this->hydrate($data);
    }
}