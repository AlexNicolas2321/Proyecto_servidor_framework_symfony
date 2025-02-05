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
            AssociationField::new('Owner')->setRequired(false), // Asignar un usuario existente
            AssociationField::new('playlistSongs') // Relación con las canciones
                ->setRequired(false)
                ->setFormTypeOptions([
                    'multiple' => true,
                    'expanded' => true,
                ])
                ->setFormTypeOptions([
                    'class' => Song::class, // Specify the target entity here
                ]),
        ];
    }
/*
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Playlist) {
            return;
        }

        // Persistir la playlist
        $entityManager->persist($entityInstance);
        $entityManager->flush();

        // Aquí puedes agregar lógica adicional si es necesario
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Playlist) {
            return;
        }

        // Aquí puedes agregar lógica adicional si es necesario
        $entityManager->flush();
    }*/ 
}
