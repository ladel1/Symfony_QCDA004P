<?php 


namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Censurator{


    private $badwords;

    public function __construct(LoadJsonService $jsonLoader,ParameterBagInterface $bag)
    {
        $jsonContent = $jsonLoader->load($bag->get("badwords_file_name"));
        
        $this->badwords = $jsonContent->insultes;      
    }


    public function purify($text,$replacement){

        // $text = preg_quote($text,'/');
        foreach ($this->badwords as $badword) {
            $pattern = "/\b".$badword."\b/i";
            $text = preg_replace($pattern,$replacement,$text);
        }
        return $text;

    }

}