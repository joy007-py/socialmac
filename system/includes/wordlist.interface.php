<?php

interface WordListInterface 
{
    /**
     * This function responsible for fetch
     * current word list data from disk
     */
    public function getAllListFileFullPath();
}