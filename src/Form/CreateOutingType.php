<?php

namespace App\Form;

use App\Entity\Location;
use App\Entity\Outing;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateOutingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'label'=> 'Nom de la sortie'
            ])
            ->add('dateTimeStartOuting', DateTimeType::class,[
                'label' => 'Date et heure de la sortie',
                'widget' => 'single_text'])
            ->add('duration', IntegerType::class,[
                'label'=> 'Durée de la sortie'
            ])
            ->add('registrationDeadLine',DateType::class,[
                'label' => 'Date fin d inscription',
                'widget' => 'single_text'
                ])
            ->add('maxRegistrations')
            ->add('description', TextareaType::class)
            ->add('location', EntityType::class,[
                'class'=>Location::class,
                'choice_label'=>'name',
                'label' => 'Lieu',
              ])
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
