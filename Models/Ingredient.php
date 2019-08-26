<?php
class Ingredient
{
    private $_id;
    private $_name;
    private $_lang;
    private $_image;
    private $_base_calories;
    private $_quantity;
    private $_unit;
    private $_unit_weight;
    private $_calories;

    public function Id()            { return $this->_id; }
    public function Name()          { return $this->_name; }
    public function Lang()          { return $this->_lang; }
    public function Image()         { return $this->_image; }
    public function BaseCalories()  { return $this->_base_calories; }
    public function Quantity()      { return $this->_quantity; }
    public function Unit()          { return $this->_unit; }
    public function UnitWeight()    { return $this->_unit_weight; }
    public function Calories()      { return $this->_calories; }

    private function setId($value)
    {
        if(preg_match('@^[0-9]+$@', $value))
        {
            $this->_id = $value;
        }
    }
    private function setName($value)
    {
        $this->_name = htmlspecialchars($value);
    }
    private function setLang($lang)
    {
        $this->_lang = htmlspecialchars($lang);
    }
    private function setImage($value)
    {
        if(preg_match('@^(.+).(png|jpg|jpeg|tiff)$@', $value))
        {
            $this->_image = $value;
        }
    }
    private function setBase_calories($value)
    {
        if(preg_match('@^[0-9]{1,3}$@', $value))
        {
            $this->_base_calories = $value;
        }
    }
    private function setQuantity($value)
    {
        if(preg_match('@^[0-9]+$@', $value))
        {
            $this->_quantity = $value;
        }
    }
    private function setUnit($value)
    {
        if(preg_match('@^(g|unité|poignée|cuillère à soupe|pincée)$@', $value))
        {
            $this->_unit = $value;
        }
    }
    private function setUnit_weight($value)
    {
        if(preg_match('@^[0-9]+$@', $value))
        {
            $this->_unit_weight = $value;
        }
    }

    private function setCalories()
    {
        switch($this->_unit)
        {
            case 'g': 
                $this->_calories = $this->_base_calories * $this->_quantity / 100;
                break;

            case 'unité':
                $this->_calories = $this->_base_calories * $this->_unit_weight * $this->_quantity / 100;
                break;

            case 'poignée':
                $this->_calories = $this->_base_calories * 30 * $this->_quantity / 100;
                break;
            
            case 'cuillère à soupe':
                $this->_calories = $this->_base_calories * 15 * $this->_quantity / 100;
                break;
            
            case 'pincée':
                $this->_calories = $this->_base_calories * 0.4 * $this->_quantity / 100;
                break;
            
            default: 
                throw new Exception('Non supported unit');
                break;
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

        if(!empty($this->_unit))
        {
            $this->setCalories();
        }
    }
}