<?php

namespace App\Form;


use App\Entity\Campus;
use App\Form\Model\FilterHome;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('campus', EntityType::class, ['class' => Campus::class, 'label' => 'Campus', 'choice_label' => 'name',
                'placeholder' => 'Tous les campus',
                'required' => false])
            ->add('outingNameLike', TextType::class, ['label' => 'Rechercher dans le nom', 'required' => false])
            ->add('startingDate', DateType::class, ['widget' => 'single_text', 'label' => 'Entre', 'required' => false, 'data' => new \DateTime("now")])
            ->add('endingDate', DateType::class, ['widget' => 'single_text', 'label' => 'Et', 'required' => false, 'data' => new \DateTime("now + 2 month")])
            ->add('isOrganizer', CheckboxType::class, ['label' => 'Sorties dont je suis l\'organisateur·rice', 'required' => false])
            ->add('isRegister', CheckboxType::class, ['label' => 'Sorties où je suis inscrit·e', 'required' => false])
            ->add('isNotRegister', CheckboxType::class, ['label' => 'Sorties où je ne suis pas inscrit·e', 'required' => false])
            ->add('pastOutings', CheckboxType::class, ['label' => 'Sorties passées', 'required' => false])
            ->add('rechercher', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FilterHome::class,
        ]);
    }
}
