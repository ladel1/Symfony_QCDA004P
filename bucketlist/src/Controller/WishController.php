<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/wish', name: 'app_wish_')]
class WishController extends AbstractController
{
    #[Route('/', name: 'list')]
    public function list(WishRepository $repo): Response
    {
        return $this->render('wish/index.html.twig',
        ["wishes"=>$repo->findBy(["isPublished"=>true],["dateCreated"=>"DESC"])]);
    }

    #[Route('/{id}', name: 'detail',requirements:["id"=>"\d+"])]
    public function detail(Wish $wish): Response
    {
        return $this->render('wish/detail.html.twig',["wish"=>$wish]);
    }   


    #[Route('/add', name: 'add')]
    public function add(Request $request,EntityManagerInterface $em): Response
    {     
        $wish = new Wish();
        $wishForm = $this->createForm(WishType::class,$wish);
        $wishForm->handleRequest($request);
        if($wishForm->isSubmitted() && $wishForm->isValid()){
            $em->persist($wish);
            $em->flush();
            $this->addFlash("success","Your idea successfully added!");
            return $this->redirectToRoute("app_wish_detail",["id"=>$wish->getId()]);
        }
        return $this->render('wish/add.html.twig',["wishForm"=>$wishForm->createView()]);
    }   

}
