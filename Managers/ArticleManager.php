<?php

class ArticleManager extends Manager
{
    public function encode($text)
    {
        //trashes
        $text = preg_replace('@"@','[m]', $text);
        $text = preg_replace('@<p><p>(.+)</p></p>@isU', '[p]$1[/p]', $text);
        $text = preg_replace('@<div><div>(.+)</div></div>@isU', '[div]$1[/div]', $text);
        $text = preg_replace('@<pre><pre>(.+)</pre></pre>@isU', '[pre]$1[/pre]', $text);
        //security
        $text = preg_replace('@<script>(.+)</script>@isU', '', $text);
        
        //headings
        $text = preg_replace('@<h1>(.+)</h1>@isU', '[h1]$1[/h1]', $text);
        $text = preg_replace('@<h2>(.+)</h2>@isU', '[h2]$1[/h2]', $text);
        $text = preg_replace('@<h3>(.+)</h3>@isU', '[h3]$1[/h3]', $text);
        $text = preg_replace('@<h4>(.+)</h4>@isU', '[h4]$1[/h4]', $text);
        $text = preg_replace('@<h5>(.+)</h5>@isU', '[h5]$1[/h5]', $text);
        $text = preg_replace('@<h6>(.+)</h6>@isU', '[h6]$1[/h6]', $text);
        //inlines
        $text = preg_replace('@<strong>(.+)</strong>@isU', '[b]$1[/b]', $text);
        $text = preg_replace('@<em>(.+)</em>@isU', '[i]$1[/i]', $text);
        $text = preg_replace('@<span style=\[m\]text-decoration: underline;\[m\]>(.+)</span>@isU', '[u]$1[/u]', $text);
        $text = preg_replace('@<span style=\[m\]text-decoration: line-through;\[m\]>(.+)</span>@isU', '[s]$1[/s]', $text);
        $text = preg_replace('@<sup>(.+)</sup>@isU', '[sup]$1[/sup]', $text);
        $text = preg_replace('@<sub>(.+)</sub>@isU', '[sub]$1[/sub]', $text);
        $text = preg_replace('@<code>(.+)</code>@isU', '[code]$1[/code]', $text);
        //blocks
        $text = preg_replace('@<p>(.+)</p>@isU', '[p]$1[/p]', $text);
        $text = preg_replace('@<blockquote>(.+)</blockquote>@isU', '[quote]$1[/quote]', $text);
        $text = preg_replace('@<div>(.+)</div>@isU', '[div]$1[/div]', $text);
        $text = preg_replace('@<pre>(.+)</pre>@isU', '[pre]$1[/pre]', $text); 

        //styles
        $text = preg_replace('@<p style=\[m\]text-align: (left|center|right|justify);\[m\]>(.+)</p>@isU', '[p $1]$2[/p]', $text);
        $text = preg_replace('@<div style=\[m\]text-align: (left|center|right|justify);\[m\]>(.+)</div>@isU', '[div $1]$2[/div]', $text);
        $text = preg_replace('@<pre style=\[m\]text-align: (left|center|right|justify);\[m\]>(.+)</pre>@isU', '[pre $1]$2[/pre]', $text);

        $text = preg_replace('@<p style=\[m\]padding-left: ([0-9]+)px;\[m\]>(.+)</p>@isU', '[p $1]$2[/p]', $text);
        $text = preg_replace('@<div style=\[m\]padding-left: ([0-9]+)px;\[m\]>(.+)</div>@isU', '[div $1]$2[/div]', $text);
        $text = preg_replace('@<pre style=\[m\]padding-left: ([0-9]+)px;\[m\]>(.+)</pre>@isU', '[pre $1]$2[/pre]', $text);

        $text = preg_replace('@<p style=\[m\]padding-left: ([0-9]+)px; text-align: (left|center|right|justify);\[m\]>(.+)</p>@isU', '[p $1 $2]$3[/p]', $text);
        $text = preg_replace('@<div style=\[m\]padding-left: ([0-9]+)px; text-align: (left|center|right|justify);\[m\]>(.+)</div>@isU', '[div $1 $2]$3[/div]', $text);
        $text = preg_replace('@<pre style=\[m\]padding-left: ([0-9]+)px; text-align: (left|center|right|justify);\[m\]>(.+)</pre>@isU', '[pre $1 $2]$3[/pre]', $text);

        $text = preg_replace('@<p style=\[m\]text-align: (left|center|right|justify); padding-left: ([0-9]+)px;\[m\]>(.+)</p>@isU', '[p $1 $2]$3[/p]', $text);
        $text = preg_replace('@<div style=\[m\]text-align: (left|center|right|justify); padding-left: ([0-9]+)px;\[m\]>(.+)</div>@isU', '[div $1 $2]$3[/div]', $text);
        $text = preg_replace('@<pre style=\[m\]text-align: (left|center|right|justify); padding-left: ([0-9]+)px;\[m\]>(.+)</pre>@isU', '[pre $1 $2]$3[/pre]', $text);
	
        return $text;
    }

    private function decode($text)
    {
        //trashes
        $text = preg_replace('@\[m\]@','"', $text);
        $text = preg_replace('@\[p\]\[p\](.+)\[/p\]\[/p\]@isU', '<p>$1</p>', $text);
        $text = preg_replace('@\[div\]\[div\](.+)\[/div\]\[/div\]@isU', '<div>$1</div>', $text);
        $text = preg_replace('@\[pre\]\[pre\](.+)\[/pre\]\[/pre\]@isU', '<pre>$1</pre>', $text);

        //headings
        $text = preg_replace('@\[h1\](.+)\[/h1\]@isU', '<h1>$1</h1>', $text);
        $text = preg_replace('@\[h2\](.+)\[/h2\]@isU', '<h2>$1</h2>', $text);
        $text = preg_replace('@\[h3\](.+)\[/h3\]@isU', '<h3>$1</h3>', $text);
        $text = preg_replace('@\[h4\](.+)\[/h4\]@isU', '<h4>$1</h4>', $text);
        $text = preg_replace('@\[h5\](.+)\[/h5\]@isU', '<h5>$1</h5>', $text);
        $text = preg_replace('@\[h6\](.+)\[/h6\]@isU', '<h6>$1</h6>', $text);
        //inlines
        $text = preg_replace('@\[b\](.+)\[/b\]@isU', '<strong>$1</strong>', $text);
        $text = preg_replace('@\[i\](.+)\[/i\]@isU', '<em>$1</em>', $text);
        $text = preg_replace('@\[u\](.+)\[/u\]@isU', '<span style="text-decoration: underline;">$1</span>', $text);
        $text = preg_replace('@\[s\](.+)\[/s\]@isU', '<span style="text-decoration: line-through;">$1</span>', $text);
        $text = preg_replace('@\[sup\](.+)\[/sup\]@isU', '<sup>$1</sup>', $text);
        $text = preg_replace('@\[sub\](.+)\[/sub\]@isU', '<sub>$1</sub>', $text);
        $text = preg_replace('@\[code\](.+)\[/code\]@isU', '<code>$1</code>', $text);
        //blocks
        $text = preg_replace('@\[p\](.+)\[/p\]@isU', '<p>$1</p>', $text);
        $text = preg_replace('@\[quote\](.+)\[/quote\]@isU', '<blockquote>$1</blockquote>', $text);
        $text = preg_replace('@\[div\](.+)\[/div\]@isU', '<div>$1</div>', $text);
        $text = preg_replace('@\[pre\](.+)\[/pre\]@isU', '<pre>$1</pre>', $text);

        //styles
        $text = preg_replace('@\[p (left|center|right|justify)\](.+)\[/p\]@isU', '<p style="text-align: $1;">$2</p>', $text);
        $text = preg_replace('@\[div (left|center|right|justify)\](.+)\[/div\]@isU', '<div style="text-align: $1;">$2</div>', $text);
        $text = preg_replace('@\[pre (left|center|right|justify)\](.+)\[/pre\]@isU', '<pre style="text-align: $1;">$2</pre>', $text);

        $text = preg_replace('@\[p ([0-9]+)\](.+)\[/p\]@isU', '<p style="padding-left: $1px;">$2</p>', $text);
        $text = preg_replace('@\[div ([0-9]+)\](.+)\[/div\]@isU', '<div style="padding-left: $1px;">$2</div>', $text);
        $text = preg_replace('@\[pre ([0-9]+)\](.+)\[/pre\]@isU', '<pre style="padding-left: $1px;">$2</pre>', $text);

        $text = preg_replace('@\[p ([0-9]+) (left|center|right|justify)\](.+)\[/p\]@isU', '<p style="padding-left: $1px; text-align: $2;">$3</p>', $text);
        $text = preg_replace('@\[div ([0-9]+) (left|center|right|justify)\](.+)\[/div\]@isU', '<div style="padding-left: $1px; text-align: $2;">$3</div>', $text);
        $text = preg_replace('@\[pre ([0-9]+) (left|center|right|justify)\](.+)\[/pre\]@isU', '<pre style="padding-left: $1px; text-align: $2;">$3</pre>', $text);

        $text = preg_replace('@\[p (left|center|right|justify) ([0-9]+)\](.+)\[/p\]@isU', '<p style="text-align: $1; padding-left: $2px;">$3</p>', $text);
        $text = preg_replace('@\[div (left|center|right|justify) ([0-9]+)\](.+)\[/div\]@isU', '<div style="text-align: $1; padding-left: $2px;">$3</div>', $text);
        $text = preg_replace('@\[pre (left|center|right|justify) ([0-9]+)\](.+)\[/pre\]@isU', '<pre style="text-align: $1; padding-left: $2px;">$3</pre>', $text);

        return $text;
    }

    //déplacer ces fonctions dans le manager et créer le DAO indépendant
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
        $q = $this->_db->prepare('SELECT COUNT(*) FROM articleappreciation WHERE article_id = :id AND isLike = 1');
        $q->bindValue(':id', htmlspecialchars($id));
        $q->execute();

        if($a = $q->fetch())
        {
            return $a[0];
        }
    }
    private function getDislikes($id)
    {
        $q = $this->_db->prepare('SELECT COUNT(*) FROM articleappreciation WHERE article_id = :id AND isLike = 0');
        $q->bindValue(':id', htmlspecialchars($id));
        $q->execute();

        if($a = $q->fetch())
        {
            return $a[0];
        }
    }
    private function getComments($id)
    {
        $q = $this->_db->prepare('SELECT * FROM articlecomment WHERE article_id = :id');
        $q->bindValue(':id', htmlspecialchars($id));
        $q->execute();

        $comments = [];

        while($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            $a['author'] = $this->getAuthor($a['user_id']);
            $comments[] = new ArticleComment($a);
        }

        return $comments;
    }
    private function getCategory($id)
    {
        $q = $this->_db->prepare('SELECT * FROM category INNER JOIN categorylocal ON category.id = categorylocal.category_id WHERE lang = :lang');
        $q->bindValue(':lang', $_GET['lang']);
        $q->execute();

        if($a = $q->fetch())
        {
            return new Category($a);
        }
    }

    public function searchArticles($search)
    {
        // cassé... à refaire
        $q = $this->_db->prepare('
            SELECT * FROM article
            INNER JOIN articlelocal
                ON article.id = articlelocal.article_id 
            WHERE articlelocal.lang = :lang 
                AND title LIKE :search
                AND articlelocal.isPublished = 1'
        );
        
        $q->bindValue(':lang', $_GET['lang']);
        $q->bindValue(':search', '%'.htmlspecialchars($search).'%');
        $q->execute();

        $results = [];
        
        while($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            $a['content']   = $this->decode($a['content']);
            $a['category']  = $this->getCategory($a['category_id']);
            $a['author']    = $this->getAuthor($a['author_id']);
            $a['likes']     = $this->getLikes($a['article_id']);
            $a['dislikes']  = $this->getDislikes($a['article_id']);
            $a['comments']  = $this->getComments($a['article_id']);
            
            $results[] = new Article($a);
        }

        return $results;
    }

    public function saveArticle($category, $author, $title, $content, $image)
    {
        $q1 = $this->_db->prepare('INSERT INTO article(category_id, author_id, image) VALUES(:category, :author, :image)');
        $q1->bindValue(':category', htmlspecialchars($category));
        $q1->bindValue(':author', htmlspecialchars($author));
        $q1->bindValue(':image', htmlspecialchars($image));
        $q1->execute();

        $content = $this->encode($content);

        $q2 = $this->_db->prepare('INSERT INTO articlelocal(article_id, lang, title, content, isPublished, date) VALUES(:id, :lang, :title, :content, 0, NOW())');
        $q2->bindValue(':id', $this->_db->lastInsertId());
        $q2->bindValue(':lang', $_GET['lang']);
        $q2->bindValue(':title', htmlspecialchars($title));
        $q2->bindValue(':content', $content);
        $q2->execute();
    }

    public function saveArticleTrad($lang, $articleId, $title, $content)
    {
        $q = $this->_db->prepare('
            INSERT INTO articlelocal(article_id, lang, title, content, isPublished, date)
            VALUES(:id, :lang, :title, :content, 0, NOW())
        ');

        $q->bindValue(':id', htmlspecialchars($articleId));
        $q->bindValue(':lang', htmlspecialchars($lang));
        $q->bindValue(':content', $this->encode($content));
        $q->bindValue(':title', htmlspecialchars($title));
        $q->execute();
    }

    public function getSimplifiedArticles()
    {
        $q = $this->_db->query('SELECT * FROM article');

        while($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            $data[] = $a;
        }
        return $data;
    }

    public function getArticleBaseVersion($id)
    {
        $q = $this->_db->prepare('
        SELECT * FROM article 
        INNER JOIN articlelocal 
        ON article.id = articlelocal.article_id
        WHERE article.id = :id
        AND articlelocal.isPublished = 1'
    );
        $q->bindValue(':id', htmlspecialchars($id));
        $q->execute();

        if($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            $a['id']        = $a['article_id'];
            $a['content']   = $this->decode($a['content']);
            $a['category']  = $this->getCategory($a['category_id']);
            $a['author']    = $this->getAuthor($a['author_id']);
            $a['likes']     = $this->getLikes($a['article_id']);
            $a['dislikes']  = $this->getDislikes($a['article_id']);
            $a['comments']  = $this->getComments($a['article_id']);

            return new Article($a);
        }
    }

    public function getArticleVersion($id, $lang)
    {
        $q = $this->_db->prepare('
            SELECT * FROM article
            INNER JOIN articlelocal
            ON articlelocal.article_id = article.id
                AND articlelocal.article_id = :id
                AND articlelocal.isPublished = 1
                AND articlelocal.lang = :lang
        ');
        $q->bindValue(':id', htmlspecialchars($id));
        $q->bindValue(':lang', htmlspecialchars($lang));
        $q->execute();

        if($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            $a['content']   = $this->decode($a['content']);
            $a['category']  = $this->getCategory($a['category_id']);
            $a['author']    = $this->getAuthor($a['author_id']);
            $a['likes']     = $this->getLikes($a['article_id']);
            $a['dislikes']  = $this->getDislikes($a['article_id']);
            $a['comments']  = $this->getComments($a['article_id']);

            return new Article($a);
        }
    }

    public function doArticleVersionExists($id, $lang)
    {
        $q = $this->_db->prepare('SELECT * FROM articlelocal WHERE article_id = :id AND lang = :lang');
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

    public function getAllArticles()
    {  
        $q = $this->_db->prepare('
            SELECT * FROM article 
            INNER JOIN articlelocal 
            ON article.id = articlelocal.article_id
            WHERE lang = :lang AND isPublished = 1
        ');

        $q->bindValue(':lang', $_GET['lang']);
        $q->execute();

        $articles = [];

        while($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            $a['content']   = $this->decode($a['content']);
            $a['category']  = $this->getCategory($a['category_id']);
            $a['author']    = $this->getAuthor($a['author_id']);
            $a['likes']     = $this->getLikes($a['article_id']);
            $a['dislikes']  = $this->getDislikes($a['article_id']);
            $a['comments']  = $this->getComments($a['article_id']);

            $articles[] = new Article($a);
        }

        return $articles;
    }

    public function getAllUnpublishedArticles()
    {
        $q = $this->_db->prepare('
            SELECT * FROM article 
            INNER JOIN articlelocal 
            ON article.id = articlelocal.article_id 
            WHERE isPublished = 0
        ');
        
        $q->bindValue(':lang', $_GET['lang']);
        $q->execute();

        $articles = [];

        while($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            $a['content']   = $this->decode($a['content']);
            $a['category']  = $this->getCategory($a['category_id']);
            $a['author']    = $this->getAuthor($a['author_id']);
            $a['likes']     = $this->getLikes($a['article_id']);
            $a['dislikes']  = $this->getDislikes($a['article_id']);
            $a['comments']  = $this->getComments($a['article_id']);

            $articles[] = new Article($a);
        }

        return $articles;

    }

    public function getPublishedArticle($id, $lang)
    {
        $q = $this->_db->prepare('
            SELECT * FROM article
            INNER JOIN articlelocal
            ON articlelocal.article_id = article.id
                AND articlelocal.id = :id
                AND articlelocal.isPublished = 1
                AND articlelocal.lang = :lang
        ');
        $q->bindValue(':id', htmlspecialchars($id));
        $q->bindValue(':lang', htmlspecialchars($lang));
        $q->execute();

        if($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            $a['content']   = $this->decode($a['content']);
            $a['category']  = $this->getCategory($a['category_id']);
            $a['author']    = $this->getAuthor($a['author_id']);
            $a['likes']     = $this->getLikes($a['article_id']);
            $a['dislikes']  = $this->getDislikes($a['article_id']);
            $a['comments']  = $this->getComments($a['article_id']);

            return new Article($a);
        }
        else
        {
            exit(header('location: javascript://history.go(-1)'));
        }
    }

    public function getUnpublishedArticle($id)
    {
        $q = $this->_db->prepare('
            SELECT * FROM article
            INNER JOIN articlelocal
            ON articlelocal.article_id = article.id
                AND articlelocal.id = :id
                AND articlelocal.isPublished = 0
        ');
        $q->bindValue(':id', htmlspecialchars($id));
        $q->execute();

        if($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            $a['content']   = $this->decode($a['content']);
            $a['category']  = $this->getCategory($a['category_id']);
            $a['author']    = $this->getAuthor($a['author_id']);
            $a['likes']     = $this->getLikes($a['article_id']);
            $a['dislikes']  = $this->getDislikes($a['article_id']);
            $a['comments']  = $this->getComments($a['article_id']);

            return new Article($a);
        }
        else
        {
            exit(header('location: javascript://history.go(-1)'));
        }
    }

    public function getMostRecentArticles()
    {
        $q = $this->_db->prepare('
            SELECT * FROM article
            INNER JOIN articlelocal
            ON articlelocal.article_id = article.id
                AND articlelocal.isPublished = 1
                AND articlelocal.lang = :lang
            ORDER BY articlelocal.date DESC
            LIMIT 0,3
        ');

        $q->bindValue(':lang', $_GET['lang']);
        $q->execute();

        $articles = [];

        while($a = $q->fetch(PDO::FETCH_ASSOC))
        {
            $a['content']   = $this->decode($a['content']);
            $a['category']  = $this->getCategory($a['category_id']);
            $a['author']    = $this->getAuthor($a['author_id']);
            $a['likes']     = $this->getLikes($a['article_id']);
            $a['dislikes']  = $this->getDislikes($a['article_id']);
            $a['comments']  = $this->getComments($a['article_id']);

            $articles[] = new Article($a);
        }

        return $articles;
    }

    public function confirmArticle($id)
    {
        $q = $this->_db->prepare('UPDATE articlelocal SET isPublished = 1 WHERE id = :id');
        $q->bindValue(':id', htmlspecialchars($id));
        $q->execute();
    }

    public function refuseArticle($id)
    {
        //on récupère l'id de l'article
        $q = $this->_db->prepare('SELECT article_id FROM articlelocal WHERE id = :id');
        $q->bindValue(':id', htmlspecialchars($id));
        $q->execute();

        $article_id = $q->fetch()[0];

        //on compte s'il y a plus d'une version de l'article à partir de l'id de l'article.
        $q = $this->_db->prepare('SELECT COUNT(id) FROM articlelocal WHERE article_id = :id');
        $q->bindValue(':id', $article_id);
        $q->execute();

        $versions = $q->fetch()[0];

        //si oui, il s'agit d'une traduction, on supprime le articlelocal sans toucher au article
        if($versions > 1)
        {
            $q = $this->_db->prepare('
                DELETE FROM articlelocal
                WHERE id = :id
            ');
            $q->bindValue(':id', $id);
            $q->execute();
        }
        //si non, il s'agit de l'article, on supprime le articlelocal, le article ET l'IMAGE !!
        else
        {
            //suppression de l'image
            $q = $this->_db->prepare('SELECT image FROM article WHERE id = :id');
            $q->bindValue(':id', $article_id);
            $q->execute();
            unlink('Ressources/img/articles/'.$q->fetch(PDO::FETCH_ASSOC)['image']);

            //supression de l'article
            $q = $this->_db->prepare('
                DELETE article, articlelocal
                FROM article
                INNER JOIN articlelocal
                ON articlelocal.article_id = :id
                WHERE article.id = :id
            ');
            $q->bindValue(':id', $article_id);
            $q->execute();
        }
    }
}