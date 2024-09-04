<?php

namespace App\Form;

use App\Entity\Profile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('photo',FileType::class,[
                'label'=> "Photo (image* jpg,png...)",
                'mapped' => false,
                'required' =>false,
                'constraints'=> [
                    new File([
                        'maxSize'=> '5M',
                        'mimeTypes'=>[
                            "image/*",
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file'
                    ])
                ]
            ])
            ->add('bio')
            ->add('location')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
        ]);
    }
}
