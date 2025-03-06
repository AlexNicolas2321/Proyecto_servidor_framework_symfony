<?php

namespace App\Controller\Admin;

use App\Entity\Style;
use App\Service\UserActivityLogger;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Doctrine\ORM\EntityManagerInterface;

class StyleCrudController extends AbstractCrudController
{
    private $userActivityLogger;

    public function __construct(UserActivityLogger $userActivityLogger)
    {
        $this->userActivityLogger = $userActivityLogger;
    }

    public static function getEntityFqcn(): string
    {
        return Style::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('Name', 'Nombre del Estilo')
                ->setHelp('Introduce un nombre claro para el estilo musical'),
            TextareaField::new('Description', 'Descripción del Estilo')
                ->setHelp('Explica brevemente las características del estilo musical.'),
            
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        parent::persistEntity($entityManager, $entityInstance);
        $this->userActivityLogger->logCrudAction(
            'created',
            'style',
            $entityInstance->getName()
        );
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        parent::updateEntity($entityManager, $entityInstance);
        $this->userActivityLogger->logCrudAction(
            'updated',
            'style',
            $entityInstance->getName()
        );
    }

    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $name = $entityInstance->getName();
        parent::deleteEntity($entityManager, $entityInstance);
        $this->userActivityLogger->logCrudAction(
            'deleted',
            'style',
            $name
        );
    }
}
