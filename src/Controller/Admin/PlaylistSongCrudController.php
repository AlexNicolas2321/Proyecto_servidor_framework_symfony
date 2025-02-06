<?php

namespace App\Controller\Admin;

use App\Entity\PlaylistSong;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PlaylistSongCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PlaylistSong::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('playlist', 'Playlist')->setRequired(true)->hideOnForm(),
            AssociationField::new('song', 'Song')->setRequired(true),
        ];
    }
    
}
