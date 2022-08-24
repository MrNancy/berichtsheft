<?php

namespace App\Controller\Admin;

use App\Entity\Eintrag;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\WeekType;

class EintragCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Eintrag::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return parent::configureActions($actions)
                     ->add(Action::INDEX,
                         Action::new('show', 'Zeigen', 'fas fa-lens')
                               ->linkToRoute('app_show', static function ($entity) {
                                   return [
                                       '_eintragId' => $entity->getId(),
                                   ];
                               })
                     );
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud = $crud->setEntityLabelInPlural('Einträge')
                     ->setEntityLabelInSingular('Eintrag')
                     ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig')
                     ->showEntityActionsInlined()
                     ->setDefaultSort(['fromDate' => 'ASC']);

        return parent::configureCrud($crud);
    }

    public function createEntity(string $entityFqcn)
    {
        return parent::createEntity($entityFqcn)
                     ->setDate(new \DateTime());
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        /** @var Eintrag $entityInstance */

        if ($entityInstance->getDate() !== null) {
            parent::persistEntity($entityManager, $entityInstance);
        }

        $entityInstance = $entityInstance->setDate($entityInstance->getToDate());

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            DateField::new('fromDate')
                     ->setColumns('col-md-4')
                     ->setRequired(true)
                     ->setLabel('Von'),

            DateField::new('toDate')
                     ->setColumns('col-md-4')
                     ->setRequired(true)
                     ->setLabel('Bis'),

            DateField::new('date')
                     ->setColumns('col-md-4')
                     ->setRequired(false)
                     ->setLabel('Datum'),

            TextareaField::new('workText')
                         ->setFormType(CKEditorType::class)
                         ->setColumns('col-md-6')
                         ->setRequired(false)
                         ->renderAsHtml()
                         ->setLabel('Betriebliche Tätigkeit'),

            TextareaField::new('school')
                         ->setFormType(CKEditorType::class)
                         ->setColumns('col-md-6')
                         ->setRequired(false)
                         ->renderAsHtml()
                         ->setLabel('Berufsschule'),
        ];
    }
}
