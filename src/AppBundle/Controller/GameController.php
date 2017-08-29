<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class GameController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $move = new Move();

        $form = $this->createFormBuilder($move)
            ->add('move', TextType::class)
            ->createForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $play = $form->getData();
            $doctrine = $this->getDoctrine()->getManager();
            $doctrine->persist($play);
            $doctrine->flush();

            return $this->redirectToRoute('homepage');
        }

        $view_data = [
            'pageTitle' => 'Make a move',
            'form' => $form->createView()
        ];

        return $this->render('game/index.html.twig', $view_data);
    }

}
