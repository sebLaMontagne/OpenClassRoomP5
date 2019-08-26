<?php
class Dish
{
    private $_id;
    private $_name;
    private $_lang;
    private $_type;
    private $_shares;
    private $_preparationDuration;
    private $_image;
    private $_isPublished;
    private $_date;
    private $_author;
    public $_ingredients;
    public $_steps;
    private $_likes;
    private $_dislikes;
    private $_comments;
    private $_calories;

    public function Id()            { return $this->_id; }
    public function Name()          { return $this->_name; }
    public function Lang()          { return $this->_lang; }
    public function Type()          { return $this->_type; }
    public function Shares()        { return $this->_shares; }
    public function Duration()      { return $this->_preparationDuration; }
    public function Image()         { return $this->_image; }
    public function IsPublished()   { return $this->_isPublished; }
    public function Date()          { return $this->_date; }
    public function Author()        { return $this->_author; }
    public function Ingredient()    { return $this->_ingredients; }
    public function Steps()         { return $this->_steps; }
    public function Likes()         { return $this->_likes; }
    public function Dislikes()      { return $this->_dislikes; }
    public function Comments()      { return $this->_comments; }
    public function Calories()      { return $this->_calories; }

    private function setId($id)
    {
        if(preg_match('@^[0-9]+$@', $id))
        {
            $this->_id = $id;
        }
        else
        {
            throw new Exception('Invalid Dish ID');
        }
    }
    private function setName($name)
    {
        if(is_string($name))
        {
            $this->_name = $name;
        }
        else
        {
            throw new Exception('The dish name is not a string value');
        }
    }
    private function setLang($lang)
    {
        $this->_lang = $lang;
    }
    private function setType($type)
    {
        if(preg_match('@^(breakfast|starter|mainDish-a|mainDish-b|dessert)$@', $type))
        {
            $this->_type = $type;
        }
        else
        {
            throw new Exception('Invalid dish type');
        }
    }
    private function setShares($shares)
    {
        if(preg_match('@^[0-9]+$@', $shares))
        {
            $this->_shares = $shares;
        }
        else
        {
            throw new Exception('the dish shares should be a number');
        }
    }
    private function setPreparationDuration($duration)
    {
        if(preg_match('@^[0-9]+$@', $duration))
        {
            $this->_preparationDuration = $duration;
        }
        else
        {
            throw new Exception('the set duration should be a number');
        }
    }
    private function setImage($image)
    {
        if(preg_match('@^[0-9a-f]{13}.(png|tiff|gif|jpg|jpeg)$@', $image))
        {
            $this->_image = $image;
        }
        else
        {
            throw new Exception('Invalid Image');
        }
    }
    private function setIsPublished($isPublished)
    {
        if(preg_match('@^(0|1)$@', $isPublished))
        {
            $this->_isPublished = $isPublished;
        }
        else
        {
            throw new Exception('the isPublished propertie must be a boolean');
        }
    }
    private function setDate($date)
    {
        if(preg_match('#^([0-9]){10}$#',$date))
        {
            $this->_date = $date;
        }
        elseif(preg_match('#^20[0-9]{2}(-([0-9]{2})){2} (([0-9]{2}:){2}([0-9]{2}))$#', $date))
        {
            $this->_date = strtotime($date);
        }
        else
        {
            throw new Exception('the date don\'t respect the right format');
        }
    }
    private function setAuthor(User $author)
    {
        $this->_author = $author;
    }
    private function setIngredients(array $ingredients)
    {
        $this->_ingredients = $ingredients;
    }
    private function setSteps(array $steps)
    {
        $this->_steps = $steps;
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
    private function setComments($comments)
    {
        $this->_comments = $comments;
    }
    private function setCalories()
    {
        foreach($this->_ingredients as $ingredient)
        {
            $this->_calories += $ingredient->Calories();
        }
        $this->_calories /= $this->_shares;
        $this->_calories = round($this->_calories);
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

        if(!empty($this->_ingredients))
        {
            $this->setCalories();
        }
    }
}