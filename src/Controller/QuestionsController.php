<?php

namespace App\Controller;

use App\Entity\Questions;
use App\Form\QuestionsType;
use App\Repository\QuestionsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/questions")
 */
class QuestionsController extends AbstractController
{
    /**
     * @Route("/", name="questions_index", methods={"GET"})
     */
    public function index(QuestionsRepository $questionsRepository): Response
    {
        $userid=$this->getUser()->getID();

        $emm = $this->getDoctrine()->getManager();
        $sqll = 'SELECT * FROM questions WHERE ruid = :ruid ORDER BY ID';
        $statementt = $emm->getConnection()->prepare($sqll);
        $statementt-> bindValue('ruid', $userid);
        $statementt->execute();
        $question = $statementt->fetchAll();

        $myquestions=$questionsRepository-> findBy( array('askid' => $userid) ); // TAKIPCI SAYISI
        return $this->render('questions/index.html.twig', [
            'questions' => $question,
            'myquestions' => $myquestions,
        ]);
    }

    /**
     * @Route("/{id}/update", name="questions_update", methods="GET|POST")
     */
    public function questions_update($id,Request $request,Questions $question): Response
    {

        $em = $this->getDoctrine()->getManager();
        $sql = "UPDATE questions SET answer=:answer,answername=:answername,answerphoto=:answerphoto,status='Reply' WHERE id=:id";
        $statement = $em->getConnection()->prepare($sql);
        $statement-> bindValue('answer',$request->request->get('answer'));
        $statement-> bindValue('answername',$request->request->get('answername'));
        $statement-> bindValue('answerphoto',$request->request->get('answerphoto'));
        $statement-> bindValue('id', $id);
        $statement->execute();

        return $this->redirectToRoute('questions_index');

    }

    /**
     * @Route("/new", name="questions_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $question = new Questions();
        $form = $this->createForm(QuestionsType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($question);
            $entityManager->flush();

            return $this->redirectToRoute('questions_index');
        }

        return $this->render('questions/new.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="questions_show", methods={"GET"})
     */
    public function show(Questions $question): Response
    {
        return $this->render('questions/show.html.twig', [
            'question' => $question,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="questions_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Questions $question): Response
    {
        $form = $this->createForm(QuestionsType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('questions_index', [
                'id' => $question->getId(),
            ]);
        }

        return $this->render('questions/edit.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="questions_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Questions $question): Response
    {
        if ($this->isCsrfTokenValid('delete'.$question->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($question);
            $entityManager->flush();
        }

        return $this->redirectToRoute('questions_index');
    }
}
