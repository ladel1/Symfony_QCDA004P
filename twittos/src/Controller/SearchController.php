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
        $twittos =  $twittoRepository->searchByAuthor($keyword);
        
        // $context = (new ObjectNormalizerContextBuilder())
        //     ->withGroups('show_username')
        //     ->toArray();
        //$json = $serializer->serialize($usernames, 'json');

        $usernames = array();        
        foreach ($twittos as $twitto) {
            $usernames[] = $twitto->getAuthor()->getUsername();
        }
        return $this->json(sprintf('{"results":%s,"code":%b}',json_encode($usernames),true));
    }
}
