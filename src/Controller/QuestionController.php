<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    #[Route('/question/ask', name: 'question_form')]
    public function ask(Request $request, EntityManagerInterface $em ): Response
    {
        $question= new Question();

        $formQuestion= $this->createForm(QuestionType::class, $question);
        $formQuestion->handleRequest($request);

        if($formQuestion->isSubmitted() && $formQuestion->isValid()){
            $question->setNbResponse(0)
                    ->setRating(0)
                    ->setCreatedAt(new \DateTimeImmutable());
            $em->persist($question);
            $em->flush();
            $this->addFlash('succes', 'Votre question à été ajouté');
            return $this->redirectToRoute('home');    
        }

        return $this->render('question/index.html.twig', [
            'form' => $formQuestion->createView(),
        ]);
    }

    
    #[Route('/question/{id}', name: 'question_show')]
    public function show(Request $request, Question $question): Response{
        
            return $this->render('question/show.html.twig', ['question' => $question]);
    }    
}   