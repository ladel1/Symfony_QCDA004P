<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\OldArticle;
use App\Repository\ArticleRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;

// prefix
#[Route("/articles",name:"app_articles_")]
class ArticleController extends AbstractController
{
    private $articles;

    public function __construct()
    {
        $this->articles=[
            new OldArticle("Formation Rust","balablablalab","Dupont",new \DateTime(),true,"https://upload.wikimedia.org/wikipedia/commons/thumb/d/d5/Rust_programming_language_black_logo.svg/1200px-Rust_programming_language_black_logo.svg.png"),
            new OldArticle("Comment créer son propre systeme d'exploitation avec c/c++","lblabalbalb" ,"Adel",new \DateTime(),true,"https://terminalroot.com/assets/img/cppdaily/check-os-cpp.jpg")
        ];
    } 
    // CRUD

    #[Route('/', name: 'list')]
    public function list(ArticleRepository $repo): Response
    {
        // findAll()
        // dump and die

        
        return $this->render('article/index.html.twig', [
            'articles' => $repo->findBy([],["createdAt"=>"DESC"]),
            'count'=>$repo->count()
        ]);
    }

    // url avec un joker id
    // #[Route("/{id}",name:"details",requirements:["id"=>"\d+"])]
    // public function details(int $id,ArticleRepository $repo):Response{// méthode 1
    //     return $this->render("article/detail.html.twig",[
    //         "article"=>$repo->find($id)
    //     ]);
    // }

    #[Route("/{id}",name:"details",requirements:["id"=>"\d+"])]
    public function details(Article $article):Response{// méthode 2
        return $this->render("article/detail.html.twig",[
            "article"=>$article
        ]);
    }

    #[Route("/ajouter",name:"add")]
    public function ajouter(Request $request,EntityManagerInterface $em):Response{     
        
        if($request->isMethod("POST")){ // l'étape de validation est obligatoire!!!!!!!!!!!!!!!!!!!

            $article = new Article();
            // pour récupérer les données d'un formulaire POST faut utiliser $request->request->get() ou $request->get()
            // pour récupérer les données d'un formulaire GET faut utiliser $request->query->get()
            $article->setTitle($request->get("title"))
                    ->setContent($request->get("content"))
                    ->setAuthor($request->get("author"))
                    ->setThumbnail($request->get("thumbnail"));
            
            $em->persist($article);
            $em->flush();
            
            return $this->redirectToRoute("app_articles_list");
        }

        return $this->render("article/add.html.twig");
    }

    #[Route("/modifier/{id}",name:"edit")]
    public function edit(Article $article,Request $request,EntityManagerInterface $em):Response{     
        
        if($request->isMethod("POST")){ // l'étape de validation est obligatoire!!!!!!!!!!!!!!!!!!!

            
            // pour récupérer les données d'un formulaire POST faut utiliser $request->request->get() ou $request->get()
            // pour récupérer les données d'un formulaire GET faut utiliser $request->query->get()
            $article->setTitle($request->get("title"))
                    ->setContent($request->get("content"))
                    ->setAuthor($request->get("author"))
                    ->setThumbnail($request->get("thumbnail"));
            

            $em->flush();

            $this->addFlash("success","L'article a bien été modifié");

            return $this->redirectToRoute("app_articles_list");
        }

        return $this->render("article/edit.html.twig",["article"=>$article]);
    }


    #[Route("/supprimer/{id}",name:"delete",requirements:["id"=>"\d+"],methods:["POST"])]
    public function delete(Article $article,EntityManagerInterface $em,Request $request):Response{

        $csrf_token = $request->getPayload()->get("token");

        if(!$this->isCsrfTokenValid('delete-item', $csrf_token)){
            throw new InvalidCsrfTokenException("");
        }
        
        $em->remove($article);
        $em->flush();
        $this->addFlash("success","L'article a bien été supprimé!");
        return $this->redirectToRoute("app_articles_list");
    }

    #[Route("/rechercher",name:"search")]
    public function search(Request $request,ArticleRepository $repo):Response{
        $articles = $repo->searchWithQueryBuilder($request->get("q"));
        return $this->render("article/index.html.twig",["articles"=>$articles]);
    }


}
