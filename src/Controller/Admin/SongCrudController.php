<?php

namespace App\Controller\Admin;

use App\Entity\Song;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class SongCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Song::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('Title', 'Título'),
            TextField::new('File', 'FileName'),
            IntegerField::new('Duration', 'Duración (segundos)'),
            TextField::new('album', 'Álbum'),
            TextField::new('Author', 'Autor'),
            IntegerField::new('Replays', 'Reproducciones'),
            IntegerField::new('Likes', 'Me gusta'),
            AssociationField::new('Genre', 'Género')
                ->setRequired(false)
                ->renderAsNativeWidget()
        ];
    }
}
