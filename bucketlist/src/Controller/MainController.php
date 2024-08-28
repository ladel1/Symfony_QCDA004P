<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController{

    #[Route('/',name:'app_main_index')]
    public function index():Response{
        return $this->render("main/index.html.twig");
    }   
    
    #[Route('/about-us',name:'app_main_about_us')]
    public function aboutUs(){        
        $filename = $this->getParameter("kernel.project_dir")."/data/team.json";
        //$filename = "../data/team.json";
        $json_content = file_get_contents($filename);
        $team = json_decode($json_content,true);        
        return $this->render("main/aboutus.html.twig",["team"=>$team]);
    }

}