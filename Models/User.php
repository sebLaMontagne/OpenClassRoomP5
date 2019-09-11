<?php

class User
{
    private $_id;
    private $_name;
    private $_email;
    private $_avatar;
    private $_isTriggered;
    private $_isAdmin;
    private $_isBanned;
    private $_isCooker;
    private $_isWriter;
    private $_isTranslator;

    public function id()            { return $this->_id; }
    public function name()          { return $this->_name; }
    public function email()         { return $this->_email; }
    public function avatar()        { return $this->_avatar; }
    public function isTriggered()   { return $this->_isTriggered; }
    public function isAdmin()       { return $this->_isAdmin; }
    public function isBanned()      { return $this->_isBanned; }
    public function isCooker()      { return $this->_isCooker; }
    public function isWriter()      { return $this->_isWriter; }
    public function isTranslator()  { return $this->_isTranslator; }

    private function setId($id)
    {
        if(preg_match('@^[0-9]+$@',$id))
        {
            $this->_id = $id;
        }
        else
        {
            throw new Exception('The send id is not a numeric value');
        }
    }
    private function setName($name)
    {
        if(is_string($name) && strlen($name) > 5)
        {
            $this->_name = $name;
        }
        else
        {
            throw new Exception('Invalid username');
        }
    }
    private function setEmail($email)
    {
        if(filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $this->_email = $email;
        }
    }
    private function setAvatar($avatar)
    {
        if(preg_match('@(.*?)\.(jpg|jpeg|gif|tiff|png)@', $avatar))
        {
            $this->_avatar = $avatar;
        }
        else
        {
            throw new Exception('Invalid avatar format');
        }
    }
    private function setIsTriggered($isTriggered)
    {
        if($isTriggered == 0 || $isTriggered == 1)
        {
            $this->_isTriggered = $isTriggered;
        }
        else
        {
            throw new Exception('The "isTriggered" attribute must be a boolean value');
        }
    }
    private function setIsAdmin($isAdmin)
    {
        if($isAdmin == 0 || $isAdmin == 1)
        {
            $this->_isAdmin = $isAdmin;
        }
        else
        {
            throw new Exception('The "isAdmin" attribute must be a boolean value');
        }
    }
    private function setIsBanned($isBanned)
    {
        if($isBanned == 0 || $isBanned == 1)
        {
            $this->_isBanned = $isBanned;
        }
        else
        {
            throw new Exception('The "isAdmin" attribute must be a boolean value');
        }
    }
    private function setIsCooker($data)
    {
        if(preg_match('@^(0|1)$@', $data))
        {
            $this->_isCooker = $data;
        }
    }
    private function setIsWriter($data)
    {
        if(preg_match('@^(0|1)$@', $data))
        {
            $this->_isWriter = $data;
        }
    }
    private function setIsTranslator($data)
    {
        if(preg_match('@^(0|1)$@', $data))
        {
            $this->_isTranslator = $data;
        }
    }

    private function hydrate(array $data)
    {
        foreach($data as $key => $value)
        {
            $method = 'set'. ucfirst($key);
            if(method_exists($this, $method))
            {
                $this->$method($value);
            }
        }
    }
    public function __construct( array $data)
    {
        $this->hydrate($data);
    }

    // super interessant
    public function getJSONdata()
    {
        $var = get_object_vars($this);

        // La référence doit servir pour les problèmes de récursivité
        foreach ($var as &$value) 
        {
            // implique que les objets composant l'objet aient eux-même une méthode 'getJSONdata', via héritage c'est le plus simple
            if(is_object($value) && method_exists($value,'getJSONData'))
            {
                $value = $value->getJSONData();
            }
            // En revanche cette fonction ne traite pas récursivement les arborescences
            if(is_array($value))
            {
                foreach($value as &$el)
                {
                    if(is_object($el) && method_exists($el,'getJSONData'))
                    {
                        $el = $el->getJSONData();
                    }
                }
            }
        }

        return $var;
    }
}