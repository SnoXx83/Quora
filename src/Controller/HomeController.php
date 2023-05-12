<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $questions =[
            [
                'title'=> 'je suis une question',
                'content' => 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Doloribus qui rem odio veritatis voluptatibus! Accusamus quaerat voluptatum repellat sunt, magni libero aut laboriosam at id totam dolore deserunt rerum. Excepturi?',
                'rating' =>20,
                'author'=> [
                'name'=> 'Maxime Larquetoux',
                'avatar' =>"https://randomuser.me/api/portraits/men/77.jpg"
                ],
                'nbResponse'=> 15
            ],
            [
                'title'=> 'je suis une question',
                'content' => 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Doloribus qui rem odio veritatis voluptatibus! Accusamus quaerat voluptatum repellat sunt, magni libero aut laboriosam at id totam dolore deserunt rerum. Excepturi?',
                'rating' => -8,
                'author'=> [
                'name'=> 'Alice DesFlandres',
                'avatar' =>"https://randomuser.me/api/portraits/women/68.jpg"
                ],
                'nbResponse'=> 10
            ]
        ];

        return $this->render('home/index.html.twig', [
            'questions' => $questions,
        ]);
    }
}
