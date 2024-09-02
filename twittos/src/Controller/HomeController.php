<?php

namespace App\Controller;

use App\Entity\Twitto;
use App\Form\TwittoType;
use App\Repository\TwittoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(TwittoRepository $twittoRepo): Response
    {

        $twitto = new Twitto();
        $form = $this->createForm(TwittoType::class, $twitto);

        return $this->render('home/index.html.twig', [
            'twittoForm'=>$form,
            'twittos'=>$twittoRepo->findBy(["parentTwitto"=>null],["createdAt"=>"DESC"])
        ]);
    }
}
