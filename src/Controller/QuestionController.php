<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Question;
use App\Entity\Vote;
use App\Form\CommentType;
use App\Form\QuestionType;
use App\Repository\VoteRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    #[Route('/question/ask', name: 'question_form')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function ask(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $question = new Question();

        $formQuestion = $this->createForm(QuestionType::class, $question);
        $formQuestion->handleRequest($request);

        if ($formQuestion->isSubmitted() && $formQuestion->isValid()) {
            $question->setNbResponse(0)
                ->setRating(0)
                ->setAuthor($user)
                ->setCreatedAt(new \DateTimeImmutable());

            $em->persist($question);
            $em->flush();
            $this->addFlash('success', 'Votre question à été ajouté');
            return $this->redirectToRoute('home');
        }

        return $this->render('question/index.html.twig', [
            'form' => $formQuestion->createView(),
        ]);
    }

    #[Route('/question/{id}', name: 'question_show')]
    public function show(Request $request, Question $question, EntityManagerInterface $em): Response
    {

        $options=[
            'question'=> $question
        ];

        $user = $this->getUser();

        if ($user) {
            $comment = new Comment();
            $commentForm = $this->createForm(CommentType::class, $comment);
            $commentForm->handleRequest($request);

            if ($commentForm->isSubmitted() && $commentForm->isValid()) {
                $comment->setCreatedAt(new DateTimeImmutable())
                    ->setRating(0)
                    ->setAuthor($user)
                    ->setQuestion($question);

                $question->setNbResponse($question->getNbResponse() + 1);

                $em->persist($comment);
                $em->flush();

                $this->addFlash('success', 'Votre réponse a été publiée.');
                return $this->redirect($request->getUri());
            }

            $options['form']= $commentForm->createView();

        }

        return $this->render('question/show.html.twig', $options);
    }

    #[Route('/question/rating/{id}/{score}', name: 'question_rating')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function questionRating(Question $question, int $score, EntityManagerInterface $em, Request $request, VoteRepository $voteRepo)
    {
        $user= $this->getUser();
        // je verifie que le current user n'est pas propriétaire de la question
        if($user !== $question->getAuthor()){
            // on verifie si l'user à déjà voté

            $vote = $voteRepo->findOneBy([
                'author' => $user,
                'question' => $question
            ]);

            if($vote){
                // on verifie qu'est ce qu'il à voté
                if(($vote->isIsLiked() && $score > 0 ) || (!$vote->isIsLiked() && $score < 0)){
                    $em->remove($vote);
                    $question->setRating($question->getRating() + ($score > 0 ? -1 : 1));
                }else{
                    $vote->setIsLiked(!$vote->isIsLiked());
                    $question->setRating($question->getRating() + ($score > 0 ? 2 : -2));
                }
            }else{
                // on crée le vote
                $vote = new Vote();
                $vote->setAuthor($user)
                        ->setQuestion($question)
                        ->setIsLiked($score > 0 ? true : false);

            $em->persist($vote);
            $question->setRating($question->getRating() + $score);
            }
            $em->flush();
        }
        
        $referer = $request->server->get('HTTP_REFERER');
        return $referer ? $this->redirect($referer) : $this->redirectToRoute('home');
    }

    #[Route('/comment/rating/{id}/{score}', name: 'comment_rating')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function commentRating(Comment $comment, int $score, EntityManagerInterface $em, Request $request, VoteRepository $voteRepo)
    {
        $user= $this->getUser();
        // je verifie que le current user n'est pas propriétaire de la question
        if($user !== $comment->getAuthor()){
            // on verifie si l'user à déjà voté

            $vote = $voteRepo->findOneBy([
                'author' => $user,
                'comment' => $comment
            ]);

            if($vote){
                // on verifie qu'est ce qu'il à voté
                if(($vote->isIsLiked() && $score > 0 ) || (!$vote->isIsLiked() && $score < 0)){
                    $em->remove($vote);
                    $comment->setRating($comment->getRating() + ($score > 0 ? -1 : 1));
                }else{
                    $vote->setIsLiked(!$vote->isIsLiked());
                    $comment->setRating($comment->getRating() + ($score > 0 ? 2 : -2));
                }
            }else{
                // on crée le vote
                $vote = new Vote();
                $vote->setAuthor($user)
                        ->setComment($comment)
                        ->setIsLiked($score > 0 ? true : false);

            $em->persist($vote);
            $comment->setRating($comment->getRating() + $score);
            }
            $em->flush();
        }

        $referer = $request->server->get('HTTP_REFERER');
        return $referer ? $this->redirect($referer) : $this->redirectToRoute('home');
    }
}
