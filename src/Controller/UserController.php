<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user/{id}', name: 'user')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function userProfile(User $user): Response
    {

        $currentUser= $this->getUser();

        if($user === $currentUser){
            return $this->redirectToRoute('current_user');
        }

        return $this->render('user/index.html.twig', [
            'controller_name' => 'Profil d\'un utilisateur',
        ]);
    }

    #[Route('/user', name: 'current_user')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function currentUserProfiles(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'Mon profil',
        ]); 
    }
}
