<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

class PostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Post::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Blog Post')
            ->setEntityLabelInPlural('Blog Posts')
            ->setSearchFields(['author', 'text', 'email'])
            ->setDefaultSort(['createdAt' => 'DESC']);
    }

    public function createEntity(string $entityFqcn): Post
    {
        return (new Post())->setCreatedBy($this->getUser());
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('conference'));
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('title');
        yield TextareaField::new('text')
            ->hideOnIndex();
        yield TextField::new('status');

        yield DateTimeField::new('publicationDate')->setFormTypeOptions([
            'html5' => true,
            'years' => range(date('Y'), (int) date('Y') + 5),
            'widget' => 'single_text',
            'input' => 'datetime_immutable',
        ]);
        $createdAt = DateTimeField::new('createdAt')->setFormTypeOptions([
            'html5' => true,
            'years' => range(date('Y'), (int) date('Y') + 5),
            'widget' => 'single_text',
            'input' => 'datetime_immutable',
        ]);
        if (Crud::PAGE_EDIT === $pageName) {
            yield $createdAt->setFormTypeOption('disabled', true);
        } else {
            yield $createdAt;
        }
    }
}
