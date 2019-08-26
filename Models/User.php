<?php

class User
{
    private $_id;
    private $_name;
    private $_email;
    private $_avatar;
    private $_isTriggered;
    private $_isAdmin;
    private $_isChief;
    private $_isBanned;

    public function id()            { return $this->_id; }
    public function name()          { return $this->_name; }
    public function email()         { return $this->_email; }
    public function avatar()        { return $this->_avatar; }
    public function isTriggered()   { return $this->_isTriggered; }
    public function isAdmin()       { return $this->_isAdmin; }
    public function isChief()       { return $this->_isChief; }
    public function isBanned()      { return $this->_isBanned; }

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

    private function setIsChief($isChief)
    {
        if($isChief == 0 || $isChief == 1)
        {
            $this->_isChief = $isChief;
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
}