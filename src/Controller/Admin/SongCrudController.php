<?php

namespace App\Controller\Admin;

use App\Entity\Song;
use App\Service\UserActivityLogger;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Doctrine\ORM\EntityManagerInterface;

class SongCrudController extends AbstractCrudController
{
    private $userActivityLogger;

    public function __construct(UserActivityLogger $userActivityLogger)
    {
        $this->userActivityLogger = $userActivityLogger;
    }

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

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        parent::persistEntity($entityManager, $entityInstance);
        $this->userActivityLogger->logCrudAction(
            'created',
            'song',
            $entityInstance->getTitle()
        );
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        parent::updateEntity($entityManager, $entityInstance);
        $this->userActivityLogger->logCrudAction(
            'updated',
            'song',
            $entityInstance->getTitle()
        );
    }

    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $title = $entityInstance->getTitle();
        parent::deleteEntity($entityManager, $entityInstance);
        $this->userActivityLogger->logCrudAction(
            'deleted',
            'song',
            $title
        );
    }
}
