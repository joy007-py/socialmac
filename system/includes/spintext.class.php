<?php

/**
 * SpinText class
 * 
 * this class is responsible for spinning text
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
     * @var string $stopWords
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
     */
    public function __construct( $text, $stopWords='', WordListInterface $wordlist )
    {
        $this->text = $text;
        $this->stopWords = $stopWords;
        $this->wordlist = $wordlist;
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

        if( empty( $this->wordListData ) )
        {
            throw new \Exception(
                'Currently no word in the system for spinning'
            );
        }

        // means we have some word list for spinning
        foreach( $this->wordListData as $key => $values )
        {
            foreach( $values as $original => $replacement )
            {
                $this->text = str_replace($original, $replacement, $this->text);
            }
        }
        
        return $this->text;
    }

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
    private function setwordListData( $key, $value )
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
            $data = $this->readFileAsStream( $path );

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
        while( $buff = fgets($handle) )
        {
            $word = trim($buff);
            $parts = explode("|", $word);
            $words[trim($parts[0])] = trim($parts[1]);
        }

        return $words;
    }
}


require __DIR__ . '/wordlist.class.php';

$data = 'UK-based indie development team, Stehlik & Co. Ltd. announces KitchenPad Timer - Timer for Cooking and Baking 4.0.11, an important update to their popular stove and oven timer app for iOS devices. KitchenPad Timer allows cooks to take back control of their stove, oven, and grill, as well as mealtime by managing multiple cook times for their oven, stovetop, and grill. The version 4.0.11 update adds several new languages, as well as performance enhancements.

United Kingdom - Independent development team, Stehlik & Co. Ltd. is proud to announce the release and immediate availability of KitchenPad(R) Timer - Timer for Cooking and Baking 4.0.11, an important update to their popular stove, oven, and grill timer app for iPhone, iPad, and iPod touch devices. KitchenPad Timer allows cooks to take back control of mealtime by managing multiple cook times for their oven, stovetop, and grill. Simply set the timer and the convenient timer displays provide all the critical timer info at a glance.

KitchenPad Timer offers a simple and elegant design, including easy-to-use controls. Users can set multiple times, so they can track the timing for multiple dishes at once. Timer options include options for stovetop, oven, and grill. The timer display shows what\'s on the stove, in the oven, or on the grill, how much cooking or baking time is left, and what temperature or heat setting you\'ve set.

"Until now, timing the cooking of multiple dishes, baked goods, and meat on the grill or in the smoker required setting multiple stopwatches, multiple timers in the Clock app on your iPhone or iPad, setting your stove\'s timer, or asking someone to let you know when a certain amount of time had passed," shares Stehlik & Co. Ltd. Director, Tomas J Stehlik. "Now, KitchenPad Timer allows you to track the timing of all of your dishes, all from a single interface!"

Users can set multiple timers via the app\'s easy-to-use interface. When the app opens, the user will see a stove from an overhead view. To set a timer for a specific burner, simply tap the burner that corresponds to the burner that\'s being used, then using the app\'s convenient touch interface, set the cooking time and the burner setting (low, medium low, medium, medium high, and high). If desired, the name of the dish or another note can be entered at the top of the screen. Then, with a quick tap of a finger, an alert sound can be selected from 50 original, custom made sounds. 

Once everything is set, tap the start button, and the timer will begin. The window closes and an animated blue gas flame of the appropriate size is displayed as the timer counts down. If the burner has been named, that information will also be displayed. Once the timer reaches the end, the alert will begin to sound, while the display shows a message telling you which dish has reached the end of its cooking time.

If oven or grill timers are needed, they can be accessed with a quick swipe or two on the screen. Up to four oven or BBQ timers can be set, using the same method as how stove top timers are set. The oven timer allows users to set an oven temperature along with a timer. Meanwhile, the BBQ timer also allows setting a burner setting, much like the stove top timer does. Users can also name each timer, using the same process as used on the stove top timer screen.

Features:
* Stunning, simple and elegant design with easy to use touchscreen controls 
* Timers for stove-top, oven, and grill
* Set, start, and pause multiple timers with ease.
* Timer displays tell you what\'s on the stove, grill, or in the oven, time remaining, and temperature or heat setting
* Five stove burner settings: low, medium low, medium, medium high, and high
* Oven temperature settings from 225 degrees to 550 degrees Fahrenheit (Can also be set to Celsius)
* Three grill burner settings: low, medium, and high
* Save favorite timers and easily recall them later when making a favorite dish
* 50 original, custom-made alarm sounds and ringtones
* Up to six independent timers for the stove, up to four for the oven, and four for the grill
* Set a timer from 1 second up to 99 hours, 59 minutes, and 59 seconds
* Select stove top style from three burner options: 4, 5, or 6 burners

Users can even customize the stove top display by selecting the number of burners displayed. By taking advantage of the app\'s convenient in-app purchases, cooks can also match the color of the on-screen stove, oven, or grill. Three colors are available to choose from, including stainless steel, white, or black.

Tracking separate cooking, baking, and grilling times has never been easier than with KitchenPad Timer. Once the settings have been customized for favorite dishes, they can be saved and recalled when needed. Take back control of mealtime with KitchenPad Timer.

Device Requirements:
* iPhone, iPad, and iPod touch
* Requires iOS 8.0 or later
* Compatible With iOS 14
* 248.2 MB

Pricing and Availability:
KitchenPad(R) Timer - Timer for Cooking and Baking 4.0.11 is only $2.99 USD (or an equivalent amount in other currencies) and is available worldwide exclusively through the App Store in the Food and Drink category. Review codes are available to journalists upon request. Convenient $0.99 in-app purchases are available to unlock the black appliance pack and white appliance pack.';

$t = new SpinText(
    $data,
    'sdfsd, sdf, sdf',
    new WordList()
);

echo $t->spin();

// print_r($t->getWordListData());