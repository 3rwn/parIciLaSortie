<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantUpdateType;
use App\Security\AppParticipantAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Curl\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class ParticipantController extends AbstractController
{
    /**
     * @Route("/participant", name="app_participant")
     */
    public function index(): Response
    {
        return $this->render('participant/index.html.twig', [
            'controller_name' => 'ParticipantController',
        ]);
    }

    /**
     * @Route("/MyProfile",  name="my_profile")
     */
    public function showMyProfile(EntityManagerInterface $em, Request $req, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppParticipantAuthenticator $authenticator): Response
    {
        $participant = $this->getUser();
        $form = $this->createForm(ParticipantUpdateType::class, $participant);
        $form->setData($participant);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('password')->getData() == '') {
                $em->persist($participant);
                $em->flush();
                return $this->redirectToRoute('home');
            }
            $participant->setPassword(
                $userPasswordHasher->hashPassword(
                    $participant,
                    $form->get('password')->getData()
                )
            );
            $em->persist($participant);
            $em->flush();

            return $userAuthenticator->authenticateUser($participant, $authenticator, $req);
            $this->redirectToRoute('home');
        }

        return $this->render('participant/MyProfile.html.twig', [
            'participant' => $participant,
            'formulaire' => $form->createView(),
        ]);
    }

    /**
     * @Route("/ShowParticipant/{id}", requirements={"id"="\d+"}, name="show_participant")
     */
    public function showParticipant(Participant $p): Response
    {
        return $this->render('participant/ShowParticipant.html.twig', [
            'participant' => $p,
        ]);
    }
}
