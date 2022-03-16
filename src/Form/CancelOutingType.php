<?php

namespace App\Form;


use App\Entity\Outing;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CancelOutingType extends AbstractType
{

    /*
     * Formulaire qui permet la supréssion d'une sortie
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reason', TextareaType::class,
                ['label' => 'Motif :', 'mapped' => false])
            ->add('submit', SubmitType::class,
                ['label' => 'Annuler la sortie']);
    }

    /*
     * Le formulaire est lié à la classe Outing
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
