<?php

namespace App\Controller;

use App\Repository\TwittoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;

class SearchController extends AbstractController
{
    #[Route('/search/{keyword}', name: 'app_search',methods:["GET"])]
    public function index(string $keyword,TwittoRepository $twittoRepository,SerializerInterface $serializer): Response
    {

        /////// avec @ ======> author || sans @ ======>content
        
        if($keyword[0] == "@"){
            $twittos =  $twittoRepository->searchByAuthor(substr($keyword,1));
            
            // $context = (new ObjectNormalizerContextBuilder())
            //     ->withGroups('show_username')
            //     ->toArray();
            //$json = $serializer->serialize($usernames, 'json');
    
            $usernames = array();        
            foreach ($twittos as $twitto) {
                $usernames[] = [$twitto->getAuthor()->getUsername()];
            }
            return $this->json(sprintf('{"results":%s,"code":%b,"type":"users"}',json_encode($usernames),true));
        }else{
            $results =  $twittoRepository->searchByContent($keyword);            
            $twittos = array();        
            foreach ($results as $twitto) {
                $twittos[] = substr($twitto->getContent(),0,50);// seulement 50 caractères
            }
            return $this->json(sprintf('{"results":%s,"code":%b,"type":"twittos"}',json_encode($twittos),true));
        }

    }
    #[Route('/s/{keyword}', name: 'app_search_page',methods:["GET"])]
    public function seachPage(string $keyword,TwittoRepository $twittoRepository): Response
    {
        return $this->render("search/index.html.twig",[
            "twittos"=>$twittoRepository->search($keyword)
        ]);
    }


}
