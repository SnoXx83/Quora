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
        $questions = [
            [
                'title' => 'je suis une question',
                'content' => "Lorem ipsum dolor sit, amet consectetur adipisicing elit. Atque ex minus voluptates illo perspiciatis voluptatibus, odio quisquam perferendis sapiente, error fugiat eveniet t",
                'rating' => -12,
                'author' => [
                    'name' => "maxime larquetoux",
                    'avatar' => "https://randomuser.me/api/portraits/men/49.jpg"
                ],
                'nbResponse' => 15
            ],
            [
                'title' => 'je suis une question',
                'content' => "Lorem ipsum dolor sit, amet consectetur adipisicing elit. Atque ex minus voluptates illo perspiciatis voluptatibus, odio quisquam perferendis sapiente, error fugiat eveniet t",
                'rating' => 15,
                'author' => [
                    'name' => "Rachid Jeffaly",
                    'avatar' => "https://randomuser.me/api/portraits/men/50.jpg"
                ],
                'nbResponse' => 12
            ]
        ];


        return $this->render('home/index.html.twig', [
            'questions' => $questions
        ]);
    }
}
