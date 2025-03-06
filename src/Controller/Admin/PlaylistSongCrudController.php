<?php

namespace App\Controller\Admin;

use App\Entity\PlaylistSong;
use App\Service\UserActivityLogger;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Doctrine\ORM\EntityManagerInterface;

class PlaylistSongCrudController extends AbstractCrudController
{
    private $userActivityLogger;

    public function __construct(UserActivityLogger $userActivityLogger)
    {
        $this->userActivityLogger = $userActivityLogger;
    }

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
    
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        parent::persistEntity($entityManager, $entityInstance);
        $this->userActivityLogger->logCrudAction(
            'added song to',
            'playlist',
            $entityInstance->getPlaylist()->getName() . ' (Song: ' . $entityInstance->getSong()->getTitle() . ')'
        );
    }

    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $details = $entityInstance->getPlaylist()->getName() . ' (Song: ' . $entityInstance->getSong()->getTitle() . ')';
        parent::deleteEntity($entityManager, $entityInstance);
        $this->userActivityLogger->logCrudAction(
            'removed song from',
            'playlist',
            $details
        );
    }
}
