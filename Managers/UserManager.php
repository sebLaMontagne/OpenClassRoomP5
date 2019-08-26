<?php

class UserManager extends Manager
{
    public function saveUser($name, $email, $password, $avatar, $lang)
    {
        if( strlen($name) > 5 &&
            strlen($password) > 7 &&
            preg_match('@[a-z]+@', $password) &&
            preg_match('@[A-Z]+@', $password) &&
            preg_match('@[0-9]+@', $password) &&
            filter_var($email, FILTER_VALIDATE_EMAIL) &&
            preg_match('@(.*?)\.(jpg|jpeg|gif|tiff|png)@', $avatar) &&
            preg_match('@(fr|en)@', $lang))
        {
            do
            {
                $token = '';
                for($i = 0; $i < 12; $i++)
                {
                    $token .= mt_rand(0,9);
                }   
            }while(!$this->isTokenFree($token));

            $q = $this->_db->prepare('
                INSERT INTO     user (name, email, password, avatar, token, isTriggered, isChief)
                VALUES          (:name, :email, :password, :avatar, :token, 0, 0)');
              
            $q->bindValue(':name', htmlspecialchars($name));
            $q->bindValue(':email', htmlspecialchars($email));
            $q->bindValue(':password', password_hash($password, PASSWORD_DEFAULT));
            $q->bindValue(':avatar', htmlspecialchars($avatar));
            $q->bindValue(':token', $token);
            $q->execute();

            $header = "From: \"Marre-mitton\"<marre-mitton@orange.fr>\n";
            $header.= "Reply-to: \"Marre-mitton\"<marre-mitton@orange.fr>\n";
            $header.= "MIME-Version: 1.0\n";
            $header.= "Content-Type: text/html;";
            mail($email, 'validation de compte', '<html><head></head><body><p>Voici votre lien d\'activation : <a href="http://localhost/P5Local/confirmRegister.'.$lang.'-'.$token.'">activer</a></p></body></html>', $header);
        }
        else
        {
            throw new Exception('Invalid parameters');
        }
    }

    public function getAllUsers()
    {
        $users = [];

        $q = $this->_db->query('SELECT * FROM user');
        while($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            $users[] = new User($a);
        }
        return $users;
    }

    public function getUserByLogins($name, $password)
    {
        $q = $this->_db->prepare('
            SELECT          *
            FROM            user
            WHERE           name = :name');
        
        $q->bindValue(':name', htmlspecialchars($name));
        $q->execute();

        if($a = $q->fetch())
        {
            if(password_verify($password, $a['password']))
            {
                return new User($a);   
            }
        }
    }

    public function getUserByToken($token)
    {
        if(preg_match('@^[0-9]{12}$@', $token))
        {
            if(!$this->isTokenFree($token))
            {
                $q = $this->_db->prepare('
                    SELECT  id, isTriggered
                    FROM    user
                    WHERE   token = :token');

                $q->bindValue(':token', $token);
                $q->execute();

                if($a = $q->fetch())
                {
                    return new User($a);
                }
            }
            else
            {
                throw new Exception('The token don\'t match any user');
            }
        }
        else
        {
            throw new Exception('Invalid token send');
        }
    }

    public function updateAvatar($id, $newAvatar)
    {
        $q = $this->_db->prepare('UPDATE user SET avatar = :avatar WHERE id = :id');
        $q->bindValue(':avatar', htmlspecialchars($newAvatar));
        $q->bindValue(':id', htmlspecialchars($id));
        $q->execute();
    }

    public function triggerUser(User $user)
    {
        if($user->id() != null && $user->isTriggered() == 0)
        {
            $q = $this->_db->prepare('
                UPDATE  user
                SET     isTriggered = 1
                WHERE   id = :id');

            $q->bindValue(':id', $user->id());
            $q->execute();
        }
    }

    public function isUsernameFree($name)
    {
        if(is_string($name))
        {
            $q = $this->_db->prepare('
                SELECT  name
                FROM    user
                WHERE   name = :name');

            $q->bindValue(':name', htmlspecialchars($name));
            $q->execute();

            return !$q->fetch();
        }
    }

    public function isEmailFree($email)
    {
        if(filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $q = $this->_db->prepare('
                SELECT  email
                FROM    user
                WHERE   email = :email');

            $q->bindValue(':email', $email);
            $q->execute();

            return !$q->fetch();
        }
    }

    public function isTokenFree($token)
    {
        if(preg_match('@^[0-9]{12}$@', $token))
        {
            $q = $this->_db->prepare('
                SELECT  token
                FROM    user
                WHERE   token = :token');

            $q->bindValue(':token', htmlspecialchars($token));
            $q->execute();

            return !$q->fetch();
        }
    }

    public function countUsers()
    {
        $q = $this->_db->query('SELECT COUNT(*) FROM user');
        return $q->fetch()[0];
    }

    public function promoteUser($id)
    {
        $q = $this->_db->prepare('UPDATE user SET isChief = 1 WHERE id = :id');
        $q->bindValue(':id', htmlspecialchars($id));
        $q->execute();
    }

    public function demoteUser($id)
    {
        $q = $this->_db->prepare('UPDATE user SET isChief = 0 WHERE id = :id');
        $q->bindValue(':id', htmlspecialchars($id));
        $q->execute();
    }

    public function banUser($id)
    {
        $q = $this->_db->prepare('UPDATE user SET isBanned = 1 WHERE id = :id');
        $q->bindValue(':id', htmlspecialchars($id));
        $q->execute();
    }

    public function debanUser($id)
    {
        $q = $this->_db->prepare('UPDATE user SET isBanned = 0 WHERE id = :id');
        $q->bindValue(':id', htmlspecialchars($id));
        $q->execute();
    }
}