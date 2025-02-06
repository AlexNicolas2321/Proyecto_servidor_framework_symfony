<?php

namespace App\Controller\Admin;

use App\Entity\Playlist;
use App\Entity\User;
use App\Entity\Song;
use App\Entity\PlaylistSong;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;

class PlaylistCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Playlist::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            TextField::new('visibility'),
            IntegerField::new('Replays', 'Replays'),
            IntegerField::new('Likes', 'Likes'),
            AssociationField::new('Owner')->setRequired(false), // Asignar un usuario existente
            CollectionField::new('playlistSongs', 'Songs')
                ->useEntryCrudForm(PlaylistSongCrudController::class)
        ];
    }

   
}
