<?php

namespace App\Controller\Admin;

use App\Entity\Playlist;
use App\Entity\User;
use App\Entity\Song;
use App\Entity\PlaylistSong;
use App\Service\UserActivityLogger;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

class PlaylistCrudController extends AbstractCrudController
{
    private $userActivityLogger;

    public function __construct(UserActivityLogger $userActivityLogger)
    {
        $this->userActivityLogger = $userActivityLogger;
    }

    public static function getEntityFqcn(): string
    {
        return Playlist::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            BooleanField::new('visibility', 'visibility'),
            IntegerField::new('Replays', 'Replays'),
            IntegerField::new('Likes', 'Likes'),
            AssociationField::new('Owner')->setRequired(false), // Asignar un usuario existente
            CollectionField::new('playlistSongs', 'Songs')
                ->useEntryCrudForm(PlaylistSongCrudController::class)
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        parent::persistEntity($entityManager, $entityInstance);
        $this->userActivityLogger->logCrudAction(
            'created',
            'playlist',
            $entityInstance->getName()
        );
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        parent::updateEntity($entityManager, $entityInstance);
        $this->userActivityLogger->logCrudAction(
            'updated',
            'playlist',
            $entityInstance->getName()
        );
    }

    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $name = $entityInstance->getName();
        parent::deleteEntity($entityManager, $entityInstance);
        $this->userActivityLogger->logCrudAction(
            'deleted',
            'playlist',
            $name
        );
    }
}
