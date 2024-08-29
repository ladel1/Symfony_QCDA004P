<?php 

namespace App\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;

class CommentController extends AbstractController{

    #[Route("/comment/add/{article}",name:"app_comment_add",methods:["POST"])]
    public function add(Request $request,EntityManagerInterface $em,Article $article):Response{

        $comment = new Comment();        
        $commentForm = $this->createForm(CommentType::class,$comment);
        $commentForm->handleRequest($request);        
        if($commentForm->isSubmitted()&&$commentForm->isValid()){
            $comment->setArticle($article);
            $em->persist($comment);
            $em->flush();
            $this->addFlash("success","Commentaire ajouté!");            
        }
        return $this->redirectToRoute("app_articles_details",["id"=>$article->getId()]);
    }

}