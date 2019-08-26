<?php
class MessageManager extends Manager
{
    private function getUserById($id)
    {
        $q = $this->_db->prepare('
            SELECT * FROM user
            WHERE id = :id
        ');
        $q->bindValue(':id', htmlspecialchars($id));
        $q->execute();

        if($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            return new User($a);
        }
    }

    public function saveMessage($type, $content, $author)
    {
        $q = $this->_db->prepare('
            INSERT INTO message(type, content, user_id, date)
            VALUES(:type, :content, :author, NOW())
        ');
        $q->bindValue(':type', htmlspecialchars($type));
        $q->bindValue(':content', htmlspecialchars($content));
        $q->bindValue(':author', htmlspecialchars($author));
        $q->execute();
    }

    public function getAllMessages()
    {
        $q = $this->_db->query('SELECT * FROM message');

        $messages = [];
        while($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            $a['author'] = $this->getUserById($a['user_id']);

            $messages[] = new Message($a);
        }
        return $messages;
    }

    public function getUserMessages($userId)
    {
        $q = $this->_db->prepare('SELECT * FROM message WHERE user_id = :id');
        $q->bindValue(':id', htmlspecialchars($userId));
        $q->execute();

        $messages = [];

        while($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            $a['author'] = $this->getUserById($a['user_id']);
            $messages[] = new Message($a);
        }
        return $messages;
    }

    public function getMessage($id)
    {
        $q = $this->_db->prepare('SELECT * FROM message WHERE id = :id');
        $q->bindValue(':id', htmlspecialchars($id));
        $q->execute();

        if($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            $a['author'] = $this->getUserById($a['user_id']);
            return new Message($a);
        }
    }

    public function deleteMessage($id)
    {
        $q = $this->_db->prepare('DELETE FROM message WHERE id = :id');
        $q->bindValue(':id', htmlspecialchars($id));
        $q->execute();
    }
}