<?php

/**
 * Wordlist Class
 * 
 * @author Joy Kumar Bera <kusjoybera@gmail.com>
 */
class WordList
{
    const FILE_ONE = 'list_one.txt';
    const FILE_TWO = 'list_two.txt';
    const FILE_THREE = 'list_three.txt';
    const FILE_FOUR = 'list_four.txt';

    public $file_dir;

    public function __construct()
    {
        $this->file_dir = dirname(__DIR__) . '/wordlist';

        if( ! is_dir($this->file_dir) )
        {
            mkdir(
                $this->file_dir,
                0777
            );
        }

        if( is_dir($this->file_dir) && ! is_writable($this->file_dir) )
        {
            chmod(
                $this->file_dir,
                0777
            );
        }
    }
}

$t = new WordList();