<?php

class CategoryManager extends Manager
{
    public function getAllCategories()
    {
        $q = $this->_db->prepare('SELECT * FROM categorylocal WHERE lang = :lang');
        $q->bindValue(':lang', $_GET['lang']);
        $q->execute();

        $categories = [];

        while($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            $a['id'] = $a['category_id'];
            $categories[] = new Category($a); 
        }
        return $categories;
    }

    public function getSimplifiedCategories()
    {
        $q = $this->_db->query('SELECT * FROM category');

        while($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            $data[] = $a;
        }
        return $data;
    }

    public function getCategoryBaseVersion($id)
    {
        $q = $this->_db->prepare('
            SELECT * FROM category 
            INNER JOIN categorylocal 
                ON category.id = categorylocal.category_id 
            WHERE category.id = :id
                AND categorylocal.isPublished = 1
        ');
        
        $q->bindValue(':id', htmlspecialchars($id));
        $q->execute();

        while($a = $q->fetch(PDO::FETCH_ASSOC))
        {
           
            $a['id'] = $a['category_id'];
            return new Category($a);
        }
    }

    public function doCategoryVersionExists($id, $lang)
    {
        $q = $this->_db->prepare('SELECT * FROM categorylocal WHERE category_id = :id AND lang = :lang');
        $q->bindValue(':id', htmlspecialchars($id));
        $q->bindValue(':lang', htmlspecialchars($lang));
        $q->execute();

        if($q->fetch())
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}