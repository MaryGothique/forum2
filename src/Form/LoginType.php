<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Build the form fields for login
        $builder
            ->add('nickname') // Field for username/nickname
            ->add('password'); // Field for password
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Configure the form options
        $resolver->setDefaults([
            'data_class' => User::class, // The data class for the form
        ]);
    }
}

