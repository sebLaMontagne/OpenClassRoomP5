<?php

class DishManager extends Manager
{
    private function getAuthor($id)
    {
        $q = $this->_db->prepare('SELECT * FROM user WHERE id = :id');
        $q->bindValue(':id', htmlspecialchars($id));
        $q->execute();

        if($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            return new User($a);
        }
    }
    private function getLikes($id)
    {
        $q = $this->_db->prepare('SELECT COUNT(*) FROM dishappreciation WHERE dish_id = :id AND isLike = 1');
        $q->bindValue(':id', htmlspecialchars($id));
        $q->execute();

        if($a = $q->fetch())
        {
            return $a[0];
        }
    }
    private function getDislikes($id)
    {
        $q = $this->_db->prepare('SELECT COUNT(*) FROM dishappreciation WHERE dish_id = :id AND isLike = 0');
        $q->bindValue(':id', htmlspecialchars($id));
        $q->execute();

        if($a = $q->fetch())
        {
            return $a[0];
        }
    }
    private function getComments($id)
    {
        $q = $this->_db->prepare('SELECT * FROM dishcomment WHERE dish_id = :id');
        $q->bindValue(':id', htmlspecialchars($id));
        $q->execute();

        $comments = [];

        while($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            $a['author'] = $this->getAuthor($a['user_id']);
            $comments[] = new DishComment($a);
        }

        return $comments;
    }

    public function searchDishes($search)
    {
        $q = $this->_db->prepare('
            SELECT * FROM dish
            INNER JOIN dishlocal
                ON dish.id = dishlocal.dish_id 
            WHERE dishlocal.lang = :lang 
                AND name LIKE :search
                AND dishlocal.isPublished = 1'
        );

        $q->bindValue(':lang', $_GET['lang']);
        $q->bindValue(':search', '%'.htmlspecialchars($search).'%');
        $q->execute();

        $results = [];
        
        while($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            $a['author'] = $this->getAuthor($a['author']);
            $a['ingredients'] = $this->getDishIngredients($a['dish_id']);
            $a['steps'] = $this->getDishSteps($a['dish_id']);
            $a['likes'] = $this->getLikes($a['dish_id']);
            $a['dislikes'] = $this->getDislikes($a['dish_id']);
            $a['comments'] = $this->getComments($a['dish_id']);

            $results[] = new Dish($a);
        }

        return $results;
    }
    
    public function saveDish($name, $type, $shares, $duration, $image, $author, $published, $lang)
    {
        if( preg_match('@^(breakfast|starter|mainDish-a|mainDish-b|dessert)$@', $type) &&
            preg_match('@^[0-9]+$@', $shares) &&
            preg_match('@^[0-9]+$@', $duration) &&
            preg_match('@^([0-9a-f]{13}).(png|tiff|jpg|jpeg|gif)$@', $image) &&
            preg_match('@^(0|1)$@', $published) &&
            preg_match('@^(fr|en)$@', $lang))
        {
            $q1 = $this->_db->prepare('
            INSERT  INTO     dish(type, shares, preparationDuration, image, author)
                    VALUES   (:type, :shares, :duration, :image, :author)');
        
            $q1->bindValue(':type', htmlspecialchars($type));
            $q1->bindValue(':shares', $shares);
            $q1->bindValue(':duration', $duration);
            $q1->bindValue(':image', $image);
            $q1->bindValue(':author', $author);

            $q1->execute();

            $q2 = $this->_db->prepare('SELECT id FROM dish WHERE image = :image');
            $q2->bindValue(':image', $image);
            $q2->execute();

            $id = $q2->fetch()['id'];

            $q2 = $this->_db->prepare('
            INSERT  INTO    dishlocal(dish_id, lang, name, isPublished, date)
                    VALUES  (:id, :lang, :name, 0, NOW())');

            $q2->bindValue(':id', $id);
            $q2->bindValue(':lang', $_GET['lang']);
            $q2->bindValue(':name', htmlspecialchars($name));

            $q2->execute();
        }
    }

    public function saveDishTrad($lang, $dish_id, $name, $steps)
    {
        $q = $this->_db->prepare('
            INSERT INTO dishlocal(dish_id, lang, name, isPublished, date)
            VALUES(:id, :lang, :name, 0, NOW())
        ');
        $q->bindValue(':id', htmlspecialchars($dish_id));
        $q->bindValue(':lang', htmlspecialchars($lang));
        $q->bindValue(':name', htmlspecialchars($name));
        $q->execute();

        foreach($steps as $key => $value)
        {
            $key++;
            $q = $this->_db->prepare('
                INSERT INTO steplocal(dish_id, dish_step_number, lang, content)
                VALUES(:id, :key, :lang, :content)
            ');
            $q->bindValue(':id', htmlspecialchars($dish_id));
            $q->bindValue(':key', $key);
            $q->bindValue(':lang', htmlspecialchars($lang));
            $q->bindValue(':content', htmlspecialchars($value));
            $q->execute();
        }
    }

    public function saveDishSteps($dishId, array $steps, $lang)
    {
        foreach($steps as $key => $value)
        {
            $q1 = $this->_db->prepare('INSERT INTO step(dish_id, stepNumber) VALUES(:id, :number)');

            $q1->bindValue(':id', htmlspecialchars($dishId));
            $q1->bindValue(':number', htmlspecialchars($key));
            $q1->execute();

            $q2 = $this->_db->prepare('INSERT INTO steplocal(dish_id, dish_step_number, lang, content) VALUES(:id, :number, :lang, :content)');

            $q2->bindValue(':id', htmlspecialchars($dishId));
            $q2->bindValue(':number', htmlspecialchars($key));
            $q2->bindValue(':lang', $_GET['lang']);
            $q2->bindValue(':content', htmlspecialchars($value));
            $q2->execute();
        }
    }

    public function getSimplifiedDishes()
    {
        $q = $this->_db->query('SELECT * FROM dish');

        while($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            $data[] = $a;
        }
        return $data;
    }

    public function doDishVersionExists($id, $lang)
    {
        $q = $this->_db->prepare('SELECT * FROM dishlocal WHERE dish_id = :id AND lang = :lang');
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

    public function getDishBaseVersion($id)
    {
        $q = $this->_db->prepare('
        SELECT * FROM dish
        INNER JOIN dishlocal
        ON dishlocal.dish_id = dish.id
        AND dish.id = :id
        WHERE dishlocal.isPublished = 1
        ');
        $q->bindValue(':id', htmlspecialchars($id));
        $q->execute();

        if($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            $a['id']            = $a['dish_id'];
            $a['author']        = $this->getAuthor($a['author']);
            $a['ingredients']   = $this->getDishIngredients($a['dish_id']);
            $a['steps']         = $this->getDishSteps($a['dish_id']);
            $a['likes']         = $this->getLikes($a['dish_id']);
            $a['dislikes']      = $this->getDislikes($a['dish_id']);
            $a['comments']      = $this->getComments($a['dish_id']);

            return new Dish($a);
        }
    }

    public function getDishVersion($id, $lang)
    {
        $q = $this->_db->prepare('
            SELECT * FROM dish
            INNER JOIN dishlocal
            ON dishlocal.dish_id = dish.id
                AND dish.id = :id
                AND dishlocal.isPublished = 1
                AND dishlocal.lang = :lang
        ');
        $q->bindValue(':id', htmlspecialchars($id));
        $q->bindValue(':lang', htmlspecialchars($lang));
        $q->execute();

        if($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            $a['author'] = $this->getAuthor($a['author']);
            $a['ingredients'] = $this->getDishIngredients($a['dish_id']);
            $a['steps'] = $this->getDishSteps($a['dish_id']);
            $a['likes'] = $this->getLikes($a['dish_id']);
            $a['dislikes'] = $this->getDislikes($a['dish_id']);
            $a['comments'] = $this->getComments($a['dish_id']);

            return new Dish($a);
        }
    }
    public function getPublishedDish($id)
    {
        $q = $this->_db->prepare('
            SELECT * FROM dish
            INNER JOIN dishlocal
            ON dishlocal.dish_id = dish.id
                AND dishlocal.id = :id
                AND dishlocal.isPublished = 1
                AND dishlocal.lang = :lang
        ');
        $q->bindValue(':id', htmlspecialchars($id));
        $q->bindValue(':lang', $_GET['lang']);
        $q->execute();

        if($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            $a['author'] = $this->getAuthor($a['author']);
            $a['ingredients'] = $this->getDishIngredients($a['dish_id']);
            $a['steps'] = $this->getDishSteps($a['dish_id']);
            $a['likes'] = $this->getLikes($a['dish_id']);
            $a['dislikes'] = $this->getDislikes($a['dish_id']);
            $a['comments'] = $this->getComments($a['dish_id']);

            return new Dish($a);
        }
        else
        {
            exit(header('location: javascript://history.go(-1)'));
        }
    }

    public function getUnpublishedDish($id)
    {
        $q = $this->_db->prepare('
            SELECT * FROM dish
            INNER JOIN dishlocal
            ON dishlocal.dish_id = dish.id
                AND dishlocal.id = :id
                AND dishlocal.isPublished = 0
        ');
        $q->bindValue(':id', htmlspecialchars($id));
        $q->execute();

        if($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            $a['author'] = $this->getAuthor($a['author']);
            $a['ingredients'] = $this->getDishIngredients($a['dish_id']);
            $a['steps'] = $this->getDishSteps($a['dish_id']);
            $a['likes'] = $this->getLikes($a['dish_id']);
            $a['dislikes'] = $this->getDislikes($a['dish_id']);
            $a['comments'] = $this->getComments($a['dish_id']);

            return new Dish($a);
        }
        else
        {
            exit(header('location: javascript://history.go(-1)'));
        }
    }

    public function getDishByName($name)
    {
        $q = $this->_db->prepare('SELECT * FROM dish INNER JOIN dishlocal ON dish.id = dishlocal.dish_id WHERE name = :name');

        $q->bindValue(':name', htmlspecialchars($name));
        $q->execute();

        if($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            $a['author'] = $this->getAuthor($a['author']);
            $a['ingredients'] = $this->getDishIngredients($a['dish_id']);
            $a['steps'] = $this->getDishSteps($a['dish_id']);
            $a['likes'] = $this->getLikes($a['dish_id']);
            $a['dislikes'] = $this->getDislikes($a['dish_id']);
            $a['comments'] = $this->getComments($a['dish_id']);
            return new Dish($a);
        }
    }

    public function getAllPublishedDishes()
    {
        $q = $this->_db->prepare('
            SELECT * FROM dish 
            INNER JOIN dishLocal 
            ON dish.id = dishlocal.dish_id 
            WHERE dishlocal.isPublished = 1
        ');
        $q->bindValue(':lang', $_GET['lang']);
        $q->execute();

        $dishes = [];
        while($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            $a['author'] = $this->getAuthor($a['author']);
            $a['ingredients'] = $this->getDishIngredients($a['dish_id']);
            $a['steps'] = $this->getDishSteps($a['dish_id']);
            $a['likes'] = $this->getLikes($a['dish_id']);
            $a['dislikes'] = $this->getDislikes($a['dish_id']);
            $a['comments'] = $this->getComments($a['dish_id']);

            $dishes[] = new Dish($a);
        }

        function comparator($object1, $object2) { return $object1->Likes() > $object2->Dislikes(); }
        usort($dishes, "comparator");

        return $dishes;
    }

    public function getAllLocalPublishedDishes()
    {
        $q = $this->_db->prepare('
            SELECT * FROM dish 
            INNER JOIN dishLocal 
            ON dish.id = dishlocal.dish_id 
            WHERE dishlocal.isPublished = 1 AND lang = :lang
        ');
        $q->bindValue(':lang', $_GET['lang']);
        $q->execute();

        $dishes = [];
        while($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            $a['author'] = $this->getAuthor($a['author']);
            $a['ingredients'] = $this->getDishIngredients($a['dish_id']);
            $a['steps'] = $this->getDishSteps($a['dish_id']);
            $a['likes'] = $this->getLikes($a['dish_id']);
            $a['dislikes'] = $this->getDislikes($a['dish_id']);
            $a['comments'] = $this->getComments($a['dish_id']);

            $dishes[] = new Dish($a);
        }

        function comparator($object1, $object2) { return $object1->Likes() > $object2->Dislikes(); }
        usort($dishes, "comparator");

        return $dishes;
    }

    public function getAllUnpublishedDishes()
    {
        $q = $this->_db->prepare('
            SELECT * FROM dish 
            INNER JOIN dishLocal 
            ON dish.id = dishlocal.dish_id
            WHERE dishlocal.isPublished = 0
            ');

        $q->execute();

        $dishes = [];

        while($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            $a['author'] = $this->getAuthor($a['author']);
            $a['ingredients'] = $this->getDishIngredients($a['dish_id']);
            $a['steps'] = $this->getDishSteps($a['dish_id']);
            $a['likes'] = $this->getLikes($a['dish_id']);
            $a['dislikes'] = $this->getDislikes($a['dish_id']);
            $a['comments'] = $this->getComments($a['dish_id']);

            $dishes[] = new Dish($a);
        }

        return $dishes;
    }
    
    public function getMostRecentDishes()
    {
        $q = $this->_db->prepare('
            SELECT * FROM dish
            INNER JOIN dishlocal
            ON dishlocal.dish_id = dish.id
                AND dishlocal.isPublished = 1
                AND dishlocal.lang = :lang
            ORDER BY dishlocal.date DESC
            LIMIT 0,3
        ');

        $q->bindValue(':lang', $_GET['lang']);
        $q->execute();

        $dishes = [];

        while($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            $a['author'] = $this->getAuthor($a['author']);
            $a['ingredients'] = $this->getDishIngredients($a['dish_id']);
            $a['steps'] = $this->getDishSteps($a['dish_id']);
            $a['likes'] = $this->getLikes($a['dish_id']);
            $a['dislikes'] = $this->getDislikes($a['dish_id']);
            $a['comments'] = $this->getComments($a['dish_id']);

            $dishes[] = new Dish($a);
        }

        return $dishes;
    }

    //refaire pour faire entrer le paramètre id dans la fonction
    public function getDishSteps($id)
    {
        $q = $this->_db->prepare('SELECT dish_step_number, content FROM steplocal WHERE dish_id = :id AND lang = :lang');
        $q->bindValue(':id', htmlspecialchars($id));
        $q->bindValue(':lang', $_GET['lang']);
        $q->execute();

        $results = [];

        while($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            $results[$a['dish_step_number']] = $a['content'];
        }

        return $results;
    }

    public function getDishIngredients($id)
    {
        $ingredients = [];

        $q = $this->_db->prepare('
            SELECT *
                FROM dish_has_ingredients
            INNER JOIN ingredientlocal
                ON dish_has_ingredients.ingredient_id = ingredientlocal.id
            INNER JOIN ingredient
                ON ingredient.id = ingredientlocal.ingredient_id
            WHERE dish_has_ingredients.dish_id = :id
                AND ingredientlocal.lang = :lang');
        
        $q->bindValue(':id', htmlspecialchars($id));
        $q->bindValue(':lang', $_GET['lang']);
        $q->execute();

        while($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            $ingredients[] = new Ingredient($a);
        }

        return $ingredients;
    }

    public function confirmDish($id)
    {
        $q = $this->_db->prepare('UPDATE dishlocal SET isPublished = 1 WHERE id = :id');
        $q->bindValue(':id', htmlspecialchars($id));
        $q->execute();
    }

    public function refuseDish($id)
    {
        //on récupère l'id et la langue du repas
        $q = $this->_db->prepare('SELECT dish_id, lang FROM dishlocal WHERE id = :id');
        $q->bindValue(':id', htmlspecialchars($id));
        $q->execute();

        if($a = $q->fetch())
        {
            $dish_id = $a[0];
            $version_lang = $a[1];
        }

        //on compte s'il y a plus d'une version du repas à partir de l'id du repas.
        $q = $this->_db->prepare('SELECT COUNT(id) FROM dishlocal WHERE dish_id = :id');
        $q->bindValue(':id', $dish_id);
        $q->execute();

        $versions = $q->fetch()[0];

        //si oui, il s'agit d'une traduction, on supprime le localdish et les steps local associées (en utilisant lang + dish_id)
        if($versions > 1)
        {
            $q = $this->_db->prepare('
                DELETE dishlocal, steplocal
                FROM dishlocal
                INNER JOIN steplocal
                ON steplocal.dish_id = dishlocal.dish_id
                    AND steplocal.lang = :lang
                WHERE dishlocal.dish_id = :id
                    AND dishlocal.lang = :lang
            ');
            $q->bindValue(':id', $dish_id);
            $q->bindValue(':lang', $version_lang);
            $q->execute();
        }
        //si non, il s'agit du repas, on supprime le dish, le dishlocal, le step, le steplocal, le dish_has_ingredient ET l'IMAGE !!
        else
        {
            //suppression de l'image
            $q = $this->_db->prepare('SELECT image FROM dish WHERE id = :id');
            $q->bindValue(':id', $dish_id);
            $q->execute();
            unlink('Ressources/img/dishes/'.$q->fetch(PDO::FETCH_ASSOC)['image']);

            $q = $this->_db->prepare('
                DELETE dish, dishlocal, step, steplocal, dish_has_ingredients
                FROM dish
                INNER JOIN dishlocal
                ON dishlocal.dish_id = :id
                INNER JOIN step
                ON step.dish_id = :id
                INNER JOIN steplocal
                ON steplocal.dish_id = :id
                INNER JOIN dish_has_ingredients
                ON dish_has_ingredients.dish_id = :id
                WHERE dish.id = :id
            ');
            $q->bindValue(':id', $dish_id);
            $q->execute();
        }
    }
}