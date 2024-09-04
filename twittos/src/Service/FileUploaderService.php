<?php 

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploaderService{

    public function __construct(
        private SluggerInterface $slugger,
        private ParameterBagInterface $bag, 
    )
    {  }

    public function upload(
        $file      
    ){
        if($file){
            $originalFilename = pathinfo( $file->getClientOriginalName(), PATHINFO_FILENAME );
            $safeFilename = $this->slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
            $uploadDirectory= $this->bag->get("upload_path");
            // Move the file to the directory where brochures are stored
            $file->move($uploadDirectory, $newFilename); 
            return $newFilename;  
        }
        return null;
    }
}