<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use App\Form\ArticleImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
 
        $builder
            ->add('title', TextType::class)
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'label' => 'Categories:',
                'multiple' => true ,
                'expanded' => true,
                'choice_label' => 'title',
                'by_reference' => true,   
            ])
            ->add('content', TextareaType::class)        
            ->add('validate', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'category' => Category::class,
        ]);
    }
}
