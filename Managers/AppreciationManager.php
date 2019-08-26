<?php

class AppreciationManager extends Manager
{
    private function getDishIdByLocalId($dishLocalId)
    {
        $q = $this->_db->prepare('
            SELECT dish_id FROM dishlocal WHERE id = :id
        ');
        $q->bindValue(':id', htmlspecialchars($dishLocalId));
        $q->execute();
        
        return $q->fetch()[0];
    }

    private function getArticleIdByLocalId($articleLocalId)
    {
        $q = $this->_db->prepare('
            SELECT article_id FROM articlelocal WHERE id = :id
        ');
        $q->bindValue(':id', htmlspecialchars($articleLocalId));
        $q->execute();
        
        return $q->fetch()[0];
    }

    public function saveDishAppreciation($dishLocalId, $userId, $isLike)
    {
        $dish_id = $this->getDishIdByLocalId($dishLocalId);

        $q = $this->_db->prepare('
            INSERT INTO dishappreciation(dish_id, user_id, isLike)
            VALUES(:dish_id, :user_id, :isLike)
        ');
        $q->bindValue(':dish_id', $dish_id);
        $q->bindValue(':user_id', htmlspecialchars($userId));
        $q->bindValue(':isLike', htmlspecialchars($isLike));
        $q->execute();
    }

    public function saveArticleAppreciation($articleLocalId, $userId, $isLike)
    {
        $article_id = $this->getArticleIdByLocalId($articleLocalId);

        $q = $this->_db->prepare('
            INSERT INTO articleappreciation(article_id, user_id, isLike)
            VALUES(:article_id, :user_id, :isLike)
        ');
        $q->bindValue(':article_id', $article_id);
        $q->bindValue(':user_id', htmlspecialchars($userId));
        $q->bindValue(':isLike', htmlspecialchars($isLike));
        $q->execute();
    }

    public function getDishAppreciation($userId, $dishLocalId)
    {
        $dish_id = $this->getDishIdByLocalId($dishLocalId);

        $q = $this->_db->prepare('
            SELECT * FROM dishappreciation
            WHERE dish_id = :dish_id
            AND user_id = :user_id
        ');
        $q->bindValue(':dish_id', $dish_id);
        $q->bindValue(':user_id', htmlspecialchars($userId));
        $q->execute();

        if($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            return new DishAppreciation($a);
        }
    }

    public function getArticleAppreciation($userId, $articleLocalId)
    {
        $article_id = $this->getArticleIdByLocalId($articleLocalId);

        $q = $this->_db->prepare('
            SELECT * FROM articleappreciation
            WHERE article_id = :article_id
            AND user_id = :user_id
        ');
        $q->bindValue(':article_id', $article_id);
        $q->bindValue(':user_id', htmlspecialchars($userId));
        $q->execute();

        if($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            return new ArticleAppreciation($a);
        }
    }

    public function updateDishAppreciation($appreciationId, $isLike)
    {
        $q = $this->_db->prepare('
            UPDATE dishappreciation
            SET isLike = :isLike
            WHERE id = :id
        ');
        $q->bindValue(':isLike', htmlspecialchars($isLike));
        $q->bindValue(':id', htmlspecialchars($appreciationId));
        $q->execute();
    }

    public function updateArticleAppreciation($appreciationId, $isLike)
    {
        $q = $this->_db->prepare('
            UPDATE articleappreciation
            SET isLike = :isLike
            WHERE id = :id
        ');
        $q->bindValue(':isLike', htmlspecialchars($isLike));
        $q->bindValue(':id', htmlspecialchars($appreciationId));
        $q->execute();
    }

    public function isDishAppreciationExists($dishLocalId, $userId, $appreciation)
    {
        $dish_id = $this->getDishIdByLocalId($dishLocalId);

        $q = $this->_db->prepare('
            SELECT * FROM dishappreciation
            WHERE dish_id = :dish_id
            AND user_id = :user_id
            AND isLike = :appreciation
        ');
        $q->bindValue(':dish_id', $dish_id);
        $q->bindValue(':user_id', htmlspecialchars($userId));
        $q->bindValue(':appreciation', htmlspecialchars($appreciation));
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

    public function isArticleAppreciationExists($articleLocalId, $userId, $appreciation)
    {
        $article_id = $this->getArticleIdByLocalId($articleLocalId);

        $q = $this->_db->prepare('
            SELECT * FROM articleappreciation
            WHERE article_id = :article_id
            AND user_id = :user_id
            AND isLike = :appreciation
        ');
        $q->bindValue(':article_id', $article_id);
        $q->bindValue(':user_id', htmlspecialchars($userId));
        $q->bindValue(':appreciation', htmlspecialchars($appreciation));
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