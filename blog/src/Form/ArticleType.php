<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class,["label"=>"Titre"])
            ->add('content',TextareaType::class,["label"=>"Contenu"])
            ->add('author',TextType::class,["label"=>"Auteur"])
            // ->add('createdAt', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('isPublished')
            ->add('thumbnail',UrlType::class,["label"=>"URL de l'image"])      
                     
            //->add('save',SubmitType::class,["label"=>"Ajouter"])         
        ;       
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
