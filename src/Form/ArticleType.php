<?php
/**
 *     The buildForm method defines the fields of the form. It adds fields for the article's title,
 *  category, and content, along with a submit button.
 *   The configureOptions method configures the options for the form. It sets the default 
 * data class to Article and the category class to Category.
 */
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

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Build the form fields
        $builder
            ->add('title', TextType::class) // Title field
            ->add('category', EntityType::class, [ // Category field
                'class' => Category::class, // The entity class for the category
                'label' => 'Categories:', // Label for the category field
                'multiple' => true, // Allow selection of multiple categories
                'expanded' => true, // Render as checkboxes/radio buttons
                'choice_label' => 'title', // Property of the Category entity to use as the choice label
                'by_reference' => true, // Pass by reference
            ])
            ->add('content', TextareaType::class) // Content field
            ->add('validate', SubmitType::class); // Submit button
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Configure the form options
        $resolver->setDefaults([
            'data_class' => Article::class, // The data class for the form
            'category' => Category::class, // The category class
        ]);
    }
}

