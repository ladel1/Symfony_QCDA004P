<?php

namespace App\Controller;

use App\Entity\Twitto;
use App\Form\TwittoType;
use App\Repository\TwittoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/twitto')]
final class TwittoController extends AbstractController
{
    #[Route(name: 'app_twitto_index', methods: ['GET'])]
    public function index(TwittoRepository $twittoRepository): Response
    {
        return $this->render('twitto/index.html.twig', [
            'twittos' => $twittoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_twitto_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $twitto = new Twitto();
        $form = $this->createForm(TwittoType::class, $twitto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $twitto->setAuthor($this->getUser());
            $entityManager->persist($twitto);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }









    #[Route('/replay/{id}', name: 'app_twitto_reply', methods: ['POST'])]
    public function reply(Twitto $twitto,Request $request, EntityManagerInterface $entityManager): Response
    {
        $twittoReplay = new Twitto();
        $form = $this->createForm(TwittoType::class, $twittoReplay);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $this->isCsrfTokenValid('reply-item', $request->getPayload()->getString('twitto__token'))) {
            $twittoReplay->setParentTwitto($twitto);
            $twittoReplay->setAuthor($this->getUser());
            $entityManager->persist($twittoReplay);
            $entityManager->flush();
        }

        
        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }

















    #[Route('/{id}', name: 'app_twitto_show', methods: ['GET'])]
    public function show(Twitto $twitto): Response
    {
        return $this->render('twitto/show.html.twig', [
            'twitto' => $twitto,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_twitto_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Twitto $twitto, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TwittoType::class, $twitto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_twitto_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('twitto/edit.html.twig', [
            'twitto' => $twitto,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_twitto_delete', methods: ['POST'])]
    public function delete(Request $request, Twitto $twitto, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$twitto->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($twitto);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_twitto_index', [], Response::HTTP_SEE_OTHER);
    }
}
