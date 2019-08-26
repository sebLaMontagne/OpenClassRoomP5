<?php

class IngredientManager extends Manager
{
    public function saveIngredient($image, $baseCalories, $unitWeight, $lang, $name)
    {
        $q = $this->_db->prepare('INSERT INTO ingredient(image, base_calories, unit_weight) VALUES(:image, :calories, :weight)');

        $q->bindValue(':image', htmlspecialchars($image));
        $q->bindValue(':calories', htmlspecialchars($baseCalories));
        $q->bindValue(':weight', htmlspecialchars($unitWeight));
        $q->execute();

        $q2 = $this->_db->prepare('INSERT INTO ingredientlocal(ingredient_id, lang, name, isPublished) VALUES(:id, :lang, :name, 0)');

        $q2->bindValue(':id', $this->_db->lastInsertId());
        $q2->bindValue(':lang', htmlspecialchars($lang));
        $q2->bindValue(':name', htmlspecialchars($name));
        $q2->execute();
    }

    public function saveDishIngredients($dishId, array $ingredients)
    {
        foreach($ingredients as $ingredient)
        {
            $q = $this->_db->prepare('INSERT INTO dish_has_ingredients(dish_id, ingredient_id, quantity, unit) VALUES(:dishId, :ingredientId, :quantity, :unit)');

            $q->bindValue(':dishId', $dishId);
            $q->bindValue(':ingredientId', $ingredient['id']);
            $q->bindValue(':quantity', $ingredient['quantity']);
            $q->bindValue(':unit', $ingredient['unit']);

            $q->execute();
        }
    }

    public function getSimplifiedIngredients()
    {
        $q = $this->_db->query('SELECT * FROM ingredient');

        while($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            $data[] = $a;
        }
        return $data;
    }

    public function doIngredientVersionExists($id, $lang)
    {
        $q = $this->_db->prepare('SELECT * FROM ingredientlocal WHERE ingredient_id = :id AND lang = :lang');
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

    public function getIngredientBaseVersion($id)
    {
        $q = $this->_db->prepare('
            SELECT * FROM ingredient 
            INNER JOIN ingredientlocal 
                ON ingredient.id = ingredientlocal.ingredient_id 
            WHERE ingredient.id = :id
            AND ingredientlocal.isPublished = 1
        ');
        
        $q->bindValue(':id', htmlspecialchars($id));
        $q->execute();

        while($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            $a['id'] = $a['ingredient_id'];
            return new Ingredient($a);
        }
    }

    public function getAllIngredients()
    {
        $ingredients = [];

        $q = $this->_db->prepare('
            SELECT * FROM ingredient 
            INNER JOIN ingredientlocal ON ingredient.id = ingredientlocal.ingredient_id 
            WHERE ingredientlocal.lang = :lang 
            AND ingredientlocal.isPublished = 1'
        );
        
        $q->bindValue(':lang', $_GET['lang']);
        $q->execute();

        while($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            $ingredients[] = new Ingredient($a);
        }

        function comparator($object1, $object2) { return $object1->Name() > $object2->Name(); }
        usort($ingredients, "comparator");

        return $ingredients;
    }

    public function getAllUnpublishedIngredients()
    {
        $ingredients = [];

        $q = $this->_db->prepare('
            SELECT * FROM ingredient 
            INNER JOIN ingredientlocal ON ingredient.id = ingredientlocal.ingredient_id 
            WHERE ingredientlocal.isPublished = 0'
        );
        
        $q->bindValue(':lang', $_GET['lang']);
        $q->execute();

        while($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            $ingredients[] = new Ingredient($a);
        }

        return $ingredients;
    }

    public function confirmIngredient($id)
    {
        $q = $this->_db->prepare('
            UPDATE ingredientlocal
            SET isPublished = 1
            WHERE id = :id
        ');
        
        $q->bindValue('id', htmlspecialchars($id));
        $q->execute();
    }

    public function deleteIngredient($id)
    {
        //suppresion de l'image
        $q = $this->_db->prepare('
            SELECT * FROM ingredientlocal
            INNER JOIN ingredient
            ON ingredient.id = ingredientlocal.ingredient_id
            WHERE ingredientlocal.id = :id
        ');

        $q->bindValue(':id', htmlspecialchars($id));
        $q->execute();

        unlink('Ressources/img/ingredients/'.$q->fetch(PDO::FETCH_ASSOC)['image']);

        //suppression de l'ingrédient
        $q = $this->_db->prepare('
            DELETE ingredient, ingredientlocal
            FROM ingredientlocal
            INNER JOIN ingredient
            ON ingredientlocal.ingredient_id = ingredient.id
            WHERE ingredientlocal.id = :id
        ');

        $q->bindValue(':id', htmlspecialchars($id));
        $q->execute();
    }
}