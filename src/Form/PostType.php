<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('text')
            ->add('publicationDate', DateType::class, [
                'input' => 'datetime_immutable',
                'widget' => 'single_text',
            ]);

        if (array_key_exists('edit', $options) && $options['edit'] === true) {
            $builder->get('publicationDate')->setDisabled(true);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'data_class' => Post::class,
                'edit' => false,
            ])
            ->addAllowedTypes('edit', 'bool');
    }
}
