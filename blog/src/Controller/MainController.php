<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController{

    #[Route('/',name:'app_main_index')]
    public function index(): Response{
        $menu = ["Home","Blog","Formations","Tutos","Connexion","Inscriptions","A-propos","Contact"];
        return $this->render("main/index.html.twig",["nav"=> $menu ]);
    }

}