<?php

class ViewModelArticleEditor extends ViewModel
{
    public $_categories;
    public $_articleIsSaved;
    public $_categoryIsSaved;

    private $_fileNewName;
    private $categoryManager;
    private $articleManager;

    private function saveFile()
    {
        $fileExtension = strtolower(pathinfo($_FILES['articleImage']['name'], PATHINFO_EXTENSION));

        if(in_array($fileExtension, $this->_authorizedExtensions) && $_FILES['articleImage']['size'] < $this->_fileMaxSize)
        {
            do
            {
                $this->_fileNewName = uniqid().'.'.$fileExtension;
                move_uploaded_file($_FILES['articleImage']['tmp_name'], $this->_uploadFolder.'/'.$this->_fileNewName);

            }while(file_exists($this->_uploadFolder.$this->_fileNewName));
        }
    }

    public function saveArticle()
    {
        $this->saveFile();
        $this->articleManager->saveArticle($_POST['category'], $_SESSION['id'], $_POST['title'], $_POST['article_content'], $this->_fileNewName);
        $this->_articleIsSaved = true;
    }

    public function saveCategory()
    {
        $this->categoryManager->saveCategory($_POST['newCategoryName']);
        $this->_categoryIsSaved = true;
    }

    public function __construct()
    {
        include('constantes.php');
        $this->_uploadFolder = $ARTICLES_FOLDER;
        $this->_authorizedExtensions = $AUTHORIZED_EXTENSIONS;
        $this->_fileMaxSize = $FILES_MAX_SIZE;

        parent::__construct();
        $this->_title = $this->_strings['ARTICLE_EDITOR_TITLE'];
        $this->_view = 'Views/articleEditor.html';

        $this->categoryManager = new CategoryManager;
        $this->articleManager = new ArticleManager;

        $this->_categories = $this->categoryManager->getAllCategories();
        $this->_articleIsSaved = false;
        $this->_categoryIsSaved = false;

    }
}