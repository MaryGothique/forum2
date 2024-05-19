<?php

namespace App\Form;
use App\Entity\Comment;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CommentType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
            {
            $builder
            ->add('title', TextType::class, [
            'label' => 'Title:',
            'attr' => [
            'placeholder' => 'Title du commentaire',
            ],
            'required' => true,
            'constraints' => [
            new Length([
            'min' => 10,
            'minMessage' => 'Le title de votre commentaire doit être
            supérieur à {{ limit }} caractères',
            'max' => 150,
            'maxMessage' => 'Le title de votre commentaire ne doit pas
            dépasser {{ limit }} caractères',
            ]),
            ],
            ])
            ->add('content', TextareaType::class, [
            'label' => 'Contenu:',
            'attr' => [
            'placeholder' => 'Contenu de votre commentaire',
            ],
            'required' => true,
            ]);
                }
                public function configureOptions(OptionsResolver $resolver): void
                {
                $resolver->setDefaults([
                'data_class' => Comment::class,
                ]);
            }
    }
