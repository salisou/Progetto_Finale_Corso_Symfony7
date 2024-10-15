<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Utente')
            ->setEntityLabelInPlural('Utenti')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('first_name')->setLabel('Nome'),
            TextField::new('last_name')->setLabel('Cognome'),
            TextField::new('email')->setLabel('Email')->onlyOnIndex(),
        ];
    }
}
