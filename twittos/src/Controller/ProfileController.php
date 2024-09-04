<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\User;
use App\Form\ProfileType;
use App\Form\ProfileUserType;
use App\Repository\ProfileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class ProfileController extends AbstractController
{


    #[Route('/profile/{id}', name: 'app_profile_show', methods: ['GET'])]
    public function show(Profile $profile): Response
    {
        return $this->render('profile/show.html.twig', [
            'profile' => $profile,
        ]);
    }

    #[Route('/my-profile', name: 'app_my_profile', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager,UserPasswordHasherInterface $passwordHasher): Response
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
                ($form->isSubmitted() && $form->isValid()) ||
                (
                    $profileUserForm->isSubmitted() && 
                    $profileUserForm->isValid() &&
                    $profileUserForm->get('plainPassword')->getData()===$profileUserForm->get('confirmPassword')->getData() &&
                    $passwordHasher->isPasswordValid($user,$profileUserForm->get('currentPassword')->getData())
                )
            ) {

             // encode the plain password
            if(!empty($profileUserForm->get('plainPassword')->getData())){
                $user->setPassword($passwordHasher->hashPassword($user, $profileUserForm->get('plainPassword')->getData()));   
            }


            $user->setProfile($profile);
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash("success","Your profile edited");
            return $this->redirectToRoute('app_my_profile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('profile/myprofile.html.twig', [
            'profile' => $profile,
            'form' => $form,
            'profileUserForm'=>$profileUserForm
        ]);
    }


}
