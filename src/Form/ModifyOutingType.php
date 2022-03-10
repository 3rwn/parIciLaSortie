<?php

namespace App\Form;

use App\Entity\Outing;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModifyOutingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
        //     ->add('dateTimeStartOuting')
        //   ->add('duration')
        //    ->add('registrationDeadLine')
        //  ->add('maxRegistrations')
            ->add('description')
        //    ->add('participants')
        //    ->add('organizer')
        //    ->add('state')
        //    ->add('location')
            ->add('campus', null, ['choice_label' => 'name'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Outing::class,
        ]);
    }
}
