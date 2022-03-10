<?php

namespace App\Form;

use App\Entity\Participant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\EqualTo;

class ParticipantUpdateType extends AbstractType
{

    /*
     * Formulaire qui récupère les informations du User connecté
     * Possibilité de mofifier n'importe quel paramètre indépendamment des autres
     * Vérifications des données cotés Front
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', null,  ['label' => 'Pseudo','attr' => ['pattern' => "[a-zA-Z0-9]{4,50}"]])
            ->add('firstName', null, ['label' => 'Prénom', 'attr' => ['pattern' => "[a-zA-Z]{1,50}"]])
            ->add('name', null, ['label' => 'Nom', 'attr' => ['pattern' => "[a-zA-Z]{1,50}"]])
            ->add('phoneNumber', null, ['label' => 'Téléphone', 'attr' => ['pattern' => "[0-9]{1,20}", 'placeholder' => 'Saisissez votre télépone']])
            ->add('email', null, ['label' => 'Mail', 'attr' => ['pattern' => "[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"]])
            ->add('password', null, ['mapped' => false, 'label' => 'Mot de passe', 'required' => false])
            ->add('confirmPassword', null, ['mapped' => false, 'label' => 'Confirmation',
                'attr' => ['placeholder' => 'Confirmez votre mdp'],
                'constraints' => [new EqualTo('password')]
            ])
            ->add('campus', null, ['choice_label' => 'name'])
            ->add('profilePicture', null, ["mapped" => false, 'label' => 'Photo de profile']);
    }

    /*
     * Le formulaire est lié à la classe Participant
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
