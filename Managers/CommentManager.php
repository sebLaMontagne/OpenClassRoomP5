<?php
class CommentManager extends Manager
{
    // Déplacer les conversions d'ID dans le Manager + voir si on peut l'automatiser
    private function getArticleIdByLocalId($articleLocalId)
    {
        $q = $this->_db->prepare('
            SELECT article_id FROM articlelocal WHERE id = :id
        ');
        $q->bindValue(':id', htmlspecialchars($articleLocalId));
        $q->execute();
        
        return $q->fetch()[0];
    }
    private function getDishIdByLocalId($dishLocalId)
    {
        $q = $this->_db->prepare('
            SELECT dish_id FROM dishlocal WHERE id = :id
        ');
        $q->bindValue(':id', htmlspecialchars($dishLocalId));
        $q->execute();
        
        return $q->fetch()[0];
    }
    private function getCommentAuthor($userId)
    {
        $q = $this->_db->prepare('
            SELECT * FROM user
            WHERE id = :id
        ');
        $q->bindValue(':id', htmlspecialchars($userId));
        $q->execute();

        if($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            return new User($a);
        }
    }

    public function saveArticleComment($localArticleId, $userId, $content)
    {
        $articleId = $this->getArticleIdByLocalId($localArticleId);

        $q = $this->_db->prepare('
            INSERT INTO articlecomment(article_id, user_id, content, isApproved)
            VALUES (:article_id, :user_id, :content, 0)
        ');
        $q->bindValue(':article_id', $articleId);
        $q->bindValue(':user_id', htmlspecialchars($userId));
        $q->bindValue(':content', htmlspecialchars($content));
        $q->execute();
    }

    public function saveDishComment($localDishId, $userId, $content)
    {
        $dishId = $this->getDishIdByLocalId($localDishId);

        $q = $this->_db->prepare('
            INSERT INTO dishcomment(dish_id, user_id, content, isApproved)
            VALUES (:dish_id, :user_id, :content, 0)
        ');
        $q->bindValue(':dish_id', $dishId);
        $q->bindValue(':user_id', htmlspecialchars($userId));
        $q->bindValue(':content', htmlspecialchars($content));
        $q->execute();
    }

    public function getAllComments()
    {
        $comments = [];

        $q = $this->_db->query('
            SELECT * FROM article
            INNER JOIN articlecomment
            ON article.id = articlecomment.article_id
            WHERE articlecomment.isApproved = 0
        ');
        while($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            $a['author'] = $this->getCommentAuthor($a['user_id']);
            $comments[] = new ArticleComment($a);
        }

        $q = $this->_db->query('
            SELECT * FROM dish
            INNER JOIN dishcomment
            ON dish.id = dishcomment.dish_id
            WHERE dishcomment.isApproved = 0
        ');
        while($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            $a['author'] = $this->getCommentAuthor($a['user_id']);
            $comments[] = new DishComment($a);
        }

        return $comments;
    }

    public function acceptDishComment($id)
    {
        $q = $this->_db->prepare('
            UPDATE dishcomment
            SET isApproved = 1
            WHERE id = :id
        ');
        $q->bindValue('id', htmlspecialchars($id));
        $q->execute();
    }

    public function acceptArticleComment($id)
    {
        $q = $this->_db->prepare('
            UPDATE articlecomment
            SET isApproved = 1
            WHERE id = :id
        ');
        $q->bindValue(':id', htmlspecialchars($id));
        $q->execute();
    }

    public function removeDishComment($id)
    {
        $q = $this->_db->prepare('
            DELETE FROM dishcomment
            WHERE id = :id
        ');
        $q->bindValue(':id', htmlspecialchars($id));
        $q->execute();
    }

    public function removeArticleComment($id)
    {
        $q = $this->_db->prepare('
            DELETE FROM articlecomment
            WHERE id = :id
        ');
        $q->bindValue(':id', htmlspecialchars($id));
        $q->execute();
    }
}