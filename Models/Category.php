<?php

class Category
{
    private $_id;
    private $_name;
    private $_lang;

    public function Id()    { return $this->_id; }
    public function Name()  { return $this->_name; }
    public function Lang()  { return $this->_lang; }

    private function setId($id)
    {
        if(preg_match('@^[1-9]+$@', $id))
        {
            $this->_id = $id;
        }
    }
    private function setName($name)
    {
        //Essayer de sécuriser en laissant passer les caractères accentués
        $this->_name = $name;
    }
    private function setLang($lang)
    {
        if(preg_match('@^[a-z]{2}$@', $lang))
        {
            $this->_lang = $lang;
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