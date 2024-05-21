<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Build the form fields
        $builder
            ->add('title'); // Title field
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Configure the form options
        $resolver->setDefaults([
            'data_class' => Category::class, // The data class for the form
        ]);
    }
}
