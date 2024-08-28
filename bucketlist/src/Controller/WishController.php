<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/wish', name: 'app_wish_')]
class WishController extends AbstractController
{
    #[Route('/', name: 'list')]
    public function list(): Response
    {
        return $this->render('wish/index.html.twig');
    }

    #[Route('/{id}', name: 'detail',requirements:["id"=>"\d+"])]
    public function detail(int $id=0): Response
    {
        return $this->render('wish/detail.html.twig');
    }    
}
