<?php 


namespace App\Controller;

use App\Entity\Like;
use App\Entity\Twitto;
use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class LikeController extends AbstractController{


    #[Route("/like/{id}",name:"app_like_dolike",methods:["POST"],requirements:["id"=>"\d+"])]
    public function doLike(Twitto $twitto,Request $request, LikeRepository $likeRepository,EntityManagerInterface $em){
        $isLiked = $likeRepository->findOneBy(["user"=>$this->getUser(),"twitto"=>$twitto]);        
        if($isLiked===null){
            $like = new Like();
            $like->setUser($this->getUser());
            $like->setTwitto($twitto);
            $em->persist($like);            
        }else{
            $em->remove($isLiked);
        }
        $em->flush();
        return $this->json( sprintf('{"status":"%b"}',$isLiked===null) ,200);
    }


}