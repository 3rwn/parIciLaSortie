<?php

namespace App\Form;

use App\Entity\Participant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;

class ParticipantUpdateType extends AbstractType
{
//    private $security;
//    private $token;

//    public function __construct(Security $security)
//    {
//        $this->security=$security;
//    }

//    public function __construct(TokenStorageInterface $token)
//    {
//        $this->token = $token;
//    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
//        $user = $this->security->getUser();
//        $user = new Participant();
//        $user = $this->token->getToken()->getUser();
        $builder

            ->add('pseudo')
            ->add('firstName',null, ['label'=>'Prénom'])
            ->add('name', null, ['label'=>'Nom'])
            ->add('phoneNumber', null, ['label'=>'Téléphone'])
            ->add('email',null, ['label'=>'Mail'])
            ->add('password', null, ['label'=>'Mot de passe', 'attr' => array(
                'placeholder' => ' ')])
            ->add('confirmPassword', null,["mapped"=>false, 'label'=>'Confirmation', 'attr' => array(
                'placeholder' => '')])
            ->add('campus', null,['choice_label'=>'name'])
            ->add('profilePicture', null, ["mapped"=>false, 'label'=>'Photo de profile'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
