<?php

/**
 * SpinText class
 * 
 * This class is responsible for spinning text
 * in the article
 * 
 * @author Joy Kumar Bera <kusjoybera@gamil.com>
 */
class SpinText
{
    /**
     * @var string $text
     */
    private $text; 
    
    /** 
     * @var array $stopWords
     */
    private $stopWords;

    /**
     * @var WordListInterface $wordlist
     */
    private $wordlist;

    /**
     * @var array $wordListData
     */
    private $wordListData = [];

    /**
     * Constructor
     * 
     * @param string $text
     * @param string $stopWords
     * @param WordListInterface $wordlist
     */
    public function __construct($text, $stopWords='', WordListInterface $wordlist)
    {
        $this->setText($text);
        $this->setStopWords($stopWords);
        $this->wordlist = $wordlist;
    }

    /**
     * Set article text
     * 
     * @param string $text
     * @throws Exception
     */
    public function setText($text)
    {
        if( empty($text) )
        {
            throw new \Exception(
                'article can not be empty'
            );
        }

        $this->text = $text;
    }

    /**
     * Set stop words
     * 
     * @param string $words
     */
    public function setStopWords($words)
    {
        $stopWordsList = [];

        if( !empty($words) )
        {
            $stopWordsList = explode(',', rtrim($words, ','));
            array_walk($stopWordsList, 'trim');
        }

        $this->stopWords = $stopWordsList;        
    }

    /**
     * Getter for stopwords
     * 
     * @return string|array
     */
    public function getStopWords()
    {
        return $this->stopWords;
    }

    /**
     * Spin article text
     * 
     * @return string
     * @throws Exception
     */
    public function spin()
    {   
        $this->gatherWordList();

        if( empty($this->wordListData) )
        {
            throw new \Exception(
                'Currently no word in the system for spinning'
            );
        }

        // so now we have some word list for spinning
        foreach( $this->wordListData as $key => $values )
        {
            foreach( $values as $original => $replacement )
            {
                // check for stop words
                if( !empty($this->stopWords) )
                {
                    if (in_array($original, $this->stopWords))
                    {
                        continue;
                    }
                }

                $this->text = str_replace($original, $replacement, $this->text);
            }
        }
        
        return $this->text;
    }

    /**
     * Get word list
     * 
     * @return array
     */
    public function getWordListData()
    {
        return $this->wordListData;
    }

    /**
     * Set wordlist data
     * 
     * @param string $key
     * @param string $value
     */
    private function setwordListData($key, $value)
    {
        $this->wordListData[$key] = $value;
    }

    /**
     * Gather word list
     */
    private function gatherWordList()
    {
        $currentFiles = $this->wordlist->getAllListFileFullPath();

        foreach( $currentFiles as $fileName => $path )
        {
            $data = $this->readFileAsStream($path);

            if( !empty($data) )
            {
                $this->setwordListData(
                    $fileName,
                    $data
                );
            }
        }
    }

    /**
     * Read file as stream
     * 
     * @param string $file
     * @return array
     */
    private function readFileAsStream( $file )
    {
        $handle = fopen($file, 'rb');
        $words = [];
        while( $buffer = fgets($handle) )
        {
            $word = trim($buffer);
            $parts = explode("|", $word);
            $words[trim($parts[0])] = trim($parts[1]);
        }

        return $words;
    }
}