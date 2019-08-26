<?php
class Message
{
    private $_id;
    private $_type;
    private $_date;
    private $_content;
    private $_author;

    public function Id()        { return $this->_id; }
    public function Type()      { return $this->_type; }
    public function Date()      { return $this->_date; }
    public function Content()   { return $this->_content; }
    public function Author()    { return $this->_author; }

    private function setId($data)
    {
        if(preg_match('@^[0-9]+$@', $data))
        {
            $this->_id = $data;
        }
    }
    private function setType($data)
    {
        $this->_type = htmlspecialchars($data);
    }
    private function setDate($data)
    {
        if(preg_match('@^[0-9]{4}(-[0-9]{2}){2} ([0-9]{2}:){2}[0-9]{2}$@', $data))
        {
            $this->_date = htmlspecialchars($data);
        }
    }
    private function setContent($data)
    {
        $this->_content = htmlspecialchars($data);
    }
    private function setAuthor(User $data)
    {
        $this->_author = $data;
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