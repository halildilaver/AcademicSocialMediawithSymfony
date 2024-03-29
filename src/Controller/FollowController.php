<?php

namespace App\Controller;

use App\Entity\Follow;
use App\Form\FollowType;
use App\Repository\FollowRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/follow")
 */
class FollowController extends AbstractController
{
    /**
     * @Route("/", name="follow_index", methods={"GET"})
     */
    public function index(FollowRepository $followRepository): Response
    {
        return $this->render('follow/index.html.twig', [
            'follows' => $followRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="follow_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $follow = new Follow();
        $form = $this->createForm(FollowType::class, $follow);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($follow);
            $entityManager->flush();

            return $this->redirectToRoute('user_show', [
                'id' => $follow->getFollowid(),
            ]);

        }

        return $this->render('follow/new.html.twig', [
            'follow' => $follow,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="follow_show", methods={"GET"})
     */
    public function show(Follow $follow): Response
    {
        return $this->render('follow/show.html.twig', [
            'follow' => $follow,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="follow_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Follow $follow): Response
    {
        $form = $this->createForm(FollowType::class, $follow);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('follow_index', [
                'id' => $follow->getId(),
            ]);
        }

        return $this->render('follow/edit.html.twig', [
            'follow' => $follow,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="follow_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Follow $follow): Response
    {
        if ($this->isCsrfTokenValid('delete'.$follow->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $fid=$follow->getFollowid();
            $entityManager->remove($follow);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_show', [
            'id' => $fid,
        ]);
    }
}
