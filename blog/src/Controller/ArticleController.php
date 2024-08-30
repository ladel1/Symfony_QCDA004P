<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\OldArticle;
use App\Entity\User;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

// prefix
#[Route("/articles",name:"app_articles_")]
//#[IsGranted("ROLE_USER")]
class ArticleController extends AbstractController
{

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
        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class,$comment);

        return $this->render("article/detail.html.twig",[
            "article"=>$article,
            "commentForm"=>$commentForm->createView()
        ]);
    }

    //#[IsGranted("ROLE_USER")]
    #[Route("/ajouter",name:"add")]
    public function ajouter(Request $request,EntityManagerInterface $em):Response{     

        // if(!$this->isGranted("ROLE_ADMIN")){
        //     return $this->redirectToRoute("app_login");
        // }

        // if(!$this->getUser()){
        //     return $this->redirectToRoute("app_login");
        // }
        // if(!$this->isGranted("ROLE_ADMIN")){
        //     // une fonctionalité en plus .....
        // }

        // etape 1: création de l'objet
        $article = new Article();
        // étape 2: creation formulaire
        $form = $this->createForm(ArticleType::class,$article);
        // étape 3: handle request => gestion de la requete http
        $form->handleRequest($request);
        // étape 4: validation
        if($form->isSubmitted() && $form->isValid()){
            $article->setAuthor($this->getUser());// connected user
            $em->persist($article);
            $em->flush();
            $this->addFlash("success","L'article a bien été ajouté");
            return  $this->redirectToRoute("app_articles_list");
        }

        return $this->render("article/add.html.twig",["formArticle"=>$form->createView()]);
    }

    #[Route("/modifier/{id}",name:"edit")]
    public function edit(Article $article,Request $request,EntityManagerInterface $em):Response{    
        /**
         * @var User
         */
        $user = $this->getUser();
        if( $user->getId()!==$article->getAuthor()->getId()){
            return $this->redirectToRoute("app_articles_list");
        }
        
        // etape 1: création de l'objet ----> l'objet dans les params
        // étape 2: creation formulaire
        $form = $this->createForm(ArticleType::class,$article);
        // étape 3: handle request => gestion de la requete http
        $form->handleRequest($request);
        // étape 4: validation
        if($form->isSubmitted() && $form->isValid()){
            // mettre a jour les données
            $em->flush();
            $this->addFlash("success","L'article a bien été modifié");
            return  $this->redirectToRoute("app_articles_list");
        }
        return $this->render("article/edit.html.twig",["formArticle"=>$form]);
    }


    #[Route("/supprimer/{id}",name:"delete",requirements:["id"=>"\d+"],methods:["POST"])]
    public function delete(Article $article,EntityManagerInterface $em,Request $request):Response{

        /**
         * @var User
         */
        $user = $this->getUser();
        if( $user->getId()!==$article->getAuthor()->getId()){
            return $this->redirectToRoute("app_articles_list");
        }

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
