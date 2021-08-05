<?php

namespace App;

/**
* Service (Model layer). 
* Orchestrates and organizes the Domain behavior.
* Direct client of Domain Model.
*/
class PostService
{
    public function findAllPosts()
    {
        return (new PostRepository())->findAll();
    }
}