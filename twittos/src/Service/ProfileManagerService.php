<?php 

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfileManagerService{


    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
        ){}


    public function checkUserValidity(User $user,FormInterface $profileUserForm){
        return  (
            $profileUserForm->isSubmitted() && 
            $profileUserForm->isValid() &&
            $profileUserForm->get('plainPassword')->getData()===$profileUserForm->get('confirmPassword')->getData() &&
            $this->passwordHasher->isPasswordValid($user,$profileUserForm->get('currentPassword')->getData())
        );
    }


    public function checkProfileValidity(FormInterface $form){
        return ($form->isSubmitted() && $form->isValid());
    }

    public function processUserPassword(FormInterface $profileUserForm,User $user){
        // encode the plain password
        if(!empty($profileUserForm->get('plainPassword')->getData())){
            $user->setPassword($this->passwordHasher->hashPassword($user, $profileUserForm->get('plainPassword')->getData()));   
        }
    }

}