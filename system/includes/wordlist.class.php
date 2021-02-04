<?php

require  __DIR__ . '/wordlist.interface.php';

/**
 * Wordlist Class
 * 
 * @author Joy Kumar Bera <kusjoybera@gmail.com>
 */
class WordList implements WordListInterface
{
    /**
     * @var array $fileNames
     */
    private $fileNames = array(
        'list_one',
        'list_two',
        'list_three',
        'list_four'
    );

    /**
     * @var string $file_dir
     */
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

    /**
     * Save word list data
     * 
     * @param array $data
     * @throws Exception
     */
    public function saveData($data)
    {
        if( ! is_array($data) )
        {
            throw new Exception(
                'data must be an array'
            );
        }

        foreach( $data as $key => $value )
        {
            $this->wirteListToDisk(
                $key,
                trim($value)
            );
        }
    }

    /**
     * Write data in the disk
     * 
     * @param string $name
     * @param string $data
     * @throws Exception
     */
    private function wirteListToDisk($name, $data)
    {
        $file = $this->getFileFullPath($name);

        if( file_exists($file) )
        {
            unlink($file);
        }

        $fp = fopen($file, 'w');

        if($fp === false)
        {
            throw new \Exception(
                'file can not writable'
            );
        }

        if( fwrite($fp,$data) === false )
        {
            throw new \Exception(
                'can\'t write to the file'
            );
        }

        fclose($fp);
    }

    /**
     * Get file full path using file name
     * 
     * @param string $name
     * @return string
     */
    private function getFileFullPath($name)
    {
        return $this->file_dir . '/' . $name  . '.txt';
    }

    /**
     * Get current word list data
     * 
     * @return array
     */
    public function getCurrentWordList()
    {
        $returnData = [];

        foreach( $this->fileNames as $fileName )
        {
            $file = $this->getFileFullPath($fileName);

            if( file_exists($file) )
            {
                $returnData[$fileName] = file_get_contents($file);
            }
            else
            {
                $returnData[$fileName] = '';
            }
        }

        return $returnData;
    }

    /**
     * Return current list file full path
     * 
     * @return array
     */
    public function getAllListFileFullPath()
    {
        $paths = [];

        foreach( $this->fileNames as $fileName )
        {
            $paths[$fileName] = $this->getFileFullPath($fileName);
        }

        return $paths;
    }
}