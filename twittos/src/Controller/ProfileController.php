<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\User;
use App\Form\ProfileType;
use App\Form\ProfileUserType;
use App\Repository\ProfileRepository;
use App\Repository\TwittoRepository;
use App\Service\FileUploaderService;
use App\Service\ProfileManagerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProfileController extends AbstractController
{


    #[Route('/profile/@{username}', name: 'app_profile_show',requirements:["username"=>"[a-zA-Z0-9\.\_\- ]{2,}"], methods: ['GET'])]
    public function show(User $user,TwittoRepository $twittoRepository): Response
    {
        return $this->render('profile/show.html.twig', [
            'user' => $user,
            'twittos'=> $twittoRepository->findBy(["author"=>$user],["createdAt"=>"DESC"],4)
        ]);
    }

    #[Route('/my-profile', name: 'app_my_profile', methods: ['GET', 'POST'])]
    public function edit(Request $request, 
    EntityManagerInterface $entityManager,
    ProfileManagerService $profileManager,
    FileUploaderService $fileUploader
    ): Response
    {

        /**
         * @var User
         */
        $user = $this->getUser();
        if($user->getProfile()===null){
            $profile = new Profile();
        }else{
            $profile = $user->getProfile();
        }

        $form = $this->createForm(ProfileType::class, $profile);
        $form->handleRequest($request);

        //---------

        $profileUserForm = $this->createForm(ProfileUserType::class,$user);
        $profileUserForm->handleRequest($request);

        if (
                $profileManager->checkProfileValidity($form) ||
                $profileManager->checkUserValidity($user,$profileUserForm)
            ) {

            $profileManager->processUserPassword($profileUserForm,$user);
            
            /** Start uploading image */
            try {
                $newFilename = $fileUploader->upload($form->get("photo")->getData());
                if($newFilename!==null) $profile->setPhoto($newFilename);                
            } catch (FileException $e) {
                $this->addFlash("danger","Error during uploading image!");
            }
            /** End uploading image */

            $user->setProfile($profile);
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash("success","Your profile edited");
            return $this->redirectToRoute('app_my_profile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('profile/myprofile.html.twig', [
            'profile' => $profile,
            'form' => $form,
            'profileUserForm'=>$profileUserForm,
        ]);
    }


}
