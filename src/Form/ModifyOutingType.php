<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Location;
use App\Entity\Outing;
use App\Entity\State;
use Proxies\__CG__\App\Entity\City as EntityCity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModifyOutingType extends AbstractType
{
    /*
     * Formulaire qui récupère les informations de la sortie
     * Possibilité de mofifier n'importe quel paramètre indépendamment des autres
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,
                ['label' => 'Nom de la sortie'])
            ->add('dateTimeStartOuting', DateTimeType::class,[
                'label' => 'Date et heure de la sortie',
                'widget' => 'single_text'])
            ->add('registrationDeadLine' ,DateType::class,[
                'label' => 'Date fin d inscription',
                'widget' => 'single_text'])
            ->add('maxRegistrations', IntegerType::class,[
                    'label' => 'Nombre de place'])
            ->add('duration', IntegerType::class,[
                'label' => 'Durée'])
            ->add('description', TextareaType::class)
            ->add('city', EntityType::class, array(
                'label' => 'Ville',
                'required' => true,
                'class'=>City::class,
                'choice_label'=>'name',
                'mapped'=> false,))
            ->add('campus', null, ['choice_label' => 'name'])
            ->add('location', EntityType::class,[
                'class'=>Location::class,
                'choice_label'=>'name',
                'label' => 'Lieu',])
            ->add('enregistrer', SubmitType::class,[
                'label' => 'Enregistrer'])
            ->add('saveAndAdd', SubmitType::class,[
                'label' => 'Publier la sortie']);
    }

    /*
     * Le formulaire est lié à la classe Outing
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Outing::class,]);
    }

    
}
