<?php

namespace App\Form;

use App\Entity\Post;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Symfony\Component\Translation\t;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => t('Post Title'),
            ])
            ->add('text', CKEditorType::class, [
                'label' => t('Content'),
            ])
            ->add('publicationDate', DateType::class, [
                'input' => 'datetime_immutable',
                'widget' => 'single_text',
                'label' => t('Publication date')
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
