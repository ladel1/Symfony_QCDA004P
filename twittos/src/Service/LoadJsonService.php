<?php 

namespace App\Service;

class LoadJsonService {

    public function load($filepath){
        // verifie si le fichier exisite
       $file = file_get_contents($filepath);
       return json_decode($file);
    }

}