<?php

namespace App\Form;

use App\Entity\Book;
use Doctrine\DBAL\Types\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('category', ChoiceType::class, [
                'choices' => [
                    'science-Fiction' => 'science-Fiction'
                    'Mystery' => 'Mystery',
                    'Autobiography' => 'Autobiography'
                ]
            ])
            ->add('publicationDate', DateType::class)
            ->add('published')
            ->add('author_id', EntityType::class, [
            'class' => 'App/Entity/Author',
            'choice_label' => 'username'
        ])

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
}
