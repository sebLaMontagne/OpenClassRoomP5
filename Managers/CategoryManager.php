<?php

class CategoryManager extends Manager
{
    public function saveCategory($name)
    {
        $this->_db->query('INSERT INTO category(id) VALUES (DEFAULT)');

        $q = $this->_db->prepare('
            INSERT INTO categorylocal(category_id, lang, name, isPublished)
            VALUES (:id, :lang, :name, 0)
        ');
        $q->bindValue(':id', $this->_db->lastInsertId());
        $q->bindValue(':lang', $_GET['lang']);
        $q->bindValue(':name', htmlspecialchars($name));
        $q->execute();
    }

    public function saveCategoryTrad($lang, $category_id, $name)
    {
        $q = $this->_db->prepare('
            INSERT INTO categorylocal(category_id, lang, name, isPublished)
            VALUES(:id, :lang, :name, 0)
        ');
        $q->bindValue(':id', htmlspecialchars($category_id));
        $q->bindValue(':lang', htmlspecialchars($lang));
        $q->bindValue(':name', htmlspecialchars($name));
        $q->execute();
    }

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

    public function getAllUnpublishedCategories()
    {
        $q = $this->_db->query('SELECT * FROM categorylocal WHERE isPublished = 0');

        $categories = [];

        while($a = $q->fetch(PDO::FETCH_ASSOC))
        {
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

    public function confirmCategory($id)
    {
        $q = $this->_db->prepare('UPDATE categorylocal SET isPublished = 1 WHERE id = :id');
        $q->bindValue(':id', htmlspecialchars($id));
        $q->execute();
    }

    public function refuseCategory($id)
    {
        //on récupère l'id de la catégorie
        $q = $this->_db->prepare('SELECT category_id FROM categorylocal WHERE id = :id');
        $q->bindValue(':id', htmlspecialchars($id));
        $q->execute();

        $category_id = $q->fetch()[0];

        //on compte s'il y a plus d'une version de la catégorie à partir de l'id de la catégorie.
        $q = $this->_db->prepare('SELECT COUNT(id) FROM categorylocal WHERE category_id = :id');
        $q->bindValue(':id', $category_id);
        $q->execute();

        $versions = $q->fetch()[0];

        //si oui, il s'agit d'une traduction, on supprime la categorylocal sans toucher à la category
        if($versions > 1)
        {
            $q = $this->_db->prepare('
                DELETE FROM categorylocal
                WHERE id = :id
            ');
            $q->bindValue(':id', $id);
            $q->execute();
        }
        //si non, il s'agit de la category, on supprime le categorylocal et la category
        else
        {
            //supression de la catégorie
            $q = $this->_db->prepare('
                DELETE category, categorylocal
                FROM category
                INNER JOIN categorylocal
                ON categorylocal.category_id = :id
                WHERE category.id = :id
            ');
            $q->bindValue(':id', $category_id);
            $q->execute();
        }
    }
}