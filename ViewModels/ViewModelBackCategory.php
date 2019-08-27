<?php

class ViewModelBackCategory
{
    private $categoryManager;

    public function confirmCategory($id)
    {
        $this->categoryManager->confirmCategory($id);
    }

    public function refuseCategory($id)
    {
        $this->categoryManager->refuseCategory($id);
    }

    public function __construct()
    {
        $this->categoryManager = new CategoryManager;
    }
}