<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Location;
use App\Entity\Outing;
use App\Repository\CityRepository;
use App\Repository\LocationRepository;
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

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvents;

class CreateOutingType extends AbstractType
{

        private $em;

        /**
         * @param EntityManagerInterface $em
         */
        public function __construct(EntityManagerInterface $em)
        {
            $this->em = $em;
        }


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
                'label' => 'Date de fin d\'inscription',
                'widget' => 'single_text'
                ])
            ->add('maxRegistrations', null, ['label'=> 'Nombre de places']
            )
            ->add('description', TextareaType::class)
            ->add('campus', null, ['label'=> 'Campus', 'choice_label' => 'name'])
            ->add('location', EntityType::class,[
                'class'=>Location::class,
                'choice_label'=>'name',
                'label' => 'Lieu',
              ])

            ->add('enregistrer', SubmitType::class,[
                'label' => 'Enregistrer'
              ])
              ->add('saveAndAdd', SubmitType::class,[
                  'label' => 'Publier la sortie'
              ])


//            ->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'))
//            ->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'))
        ;
    }
    protected function addElements(FormInterface $form, City $city = null)
    {

        $form->add('city', EntityType::class, array(
            'label' => 'Ville 2',
            'required' => true,
            'class'=>City::class,
//           'data' => $city,
            'choice_label'=>'name',
            'mapped'=> false,
            'placeholder' => 'Selectionnez une ville',
        ));

        $locations = array();

        if ($city) {

            $locationRepository = $this->em->getRepository('App:Location');
            $locations = $locationRepository->createQueryBuilder("q")
                ->where("q.city = :cityId")
                ->setParameter("cityId", $city->getId())
                ->getQuery()
                ->getResult();
        }

        $form->add('location', EntityType::class, array(
            'label' => 'Adresse 2',
            'required' => true,
            'placeholder' => 'Sélectionnez une adresse',
            'class' => Location::class,
//            'choice_label'=>$locations->getName()
            'choices' => $locations
        ));
    }
        function onPreSubmit(FormEvent $event)
        {
            $form = $event->getForm();
            $data = $event->getData();

            $cityRepository = $this->em->getRepository('App:City');
            $city = $cityRepository->find($data['city']);

            $this->addElements($form, $city);
        }

        function onPreSetData(FormEvent $event) {
            $outing = $event->getData();
            $form = $event->getForm();

            $city = $outing->getLocation() ? $outing->getLocation() : null;

            $this->addElements($form, $city);

        }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Outing::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_outing';
    }
}
