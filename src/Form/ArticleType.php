<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

//this is the class immplement, with the class name
class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //this is the builder pour building the article's form 
        $builder
            ->add('title', TextType::class)
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'label' => 'Categories:',
                'multiple' => true,
                'expanded' => true,
                'choice_label' => 'title',
                'by_reference' => false, // Set to false to allow adding/removing categories
            ])
            ->add('content', TextareaType::class)
            ->add('validate', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}

