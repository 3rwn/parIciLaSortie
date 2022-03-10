<?php

namespace App\Form;

use App\Entity\Participant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\File;
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
            ->add('pseudo', null,  ['label' => 'Pseudo'])
            ->add('firstName', null, ['label' => 'Prénom'])
            ->add('name', null, ['label' => 'Nom'])
            ->add('phoneNumber', null, ['label' => 'Téléphone', 'attr' => ['placeholder' => 'Saisissez votre télépone']])
            ->add('email', null, ['label' => 'Mail'])
            ->add('password', RepeatedType::class, ['mapped' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe et la confirmation ne correspondent pas.',
                'options' => ['attr' => ['placeholder' => 'Entrez votre nouveau mot de passe','class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmation'],
            ])
            ->add('campus', null, ['choice_label' => 'name'])
            ->add('profilePictureFileName', FileType::class, [
                'label' => 'Photo de profil',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpg',
                            'image/jpeg',
                            'image/gif'
                        ],
                        'mimeTypesMessage' => 'Veuillez-charger un format valide.',
                    ])
                ],
            ]);

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
