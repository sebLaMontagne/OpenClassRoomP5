<?php

class DishAppreciation
{
    private $_id;
    private $_user_id;
    private $_dish_id;
    private $_isLike;

    public function Id()        { return $this->_id; }
    public function UserId()    { return $this->_user_id; }
    public function DishId()    { return $this->_dish_id; }
    public function IsLike()    { return $this->_isLike; }

    private function setId($data)
    {
        if(preg_match('@[0-9]+@', $data))
        {
            $this->_id = $data;
        }
    }
    private function setUser_id($data)
    {
        if(preg_match('@[0-9]+@', $data))
        {
            $this->_user_id = $data;
        }
    }
    private function setDish_id($data)
    {
        if(preg_match('@[0-9]+@', $data))
        {
            $this->_dish_id = $data;
        }
    }
    private function setIsLike($data)
    {
        if(preg_match('@[0|1]@', $data))
        {
            $this->_isLike = $data;
        }
    }

    private function hydrate(array $data)
    {
        foreach($data as $key => $value)
        {
            $method = 'set'.ucfirst($key);
            if(method_exists($this,$method))
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