<?php

namespace AppBundle\Controller;

use AppBundle\Entity\App\Round;
use AppBundle\Repository\App\RoundRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Jarrett\RockPaperScissorsSpockLizard\Game;
use Jarrett\RockPaperScissorsSpockLizard\Player;
use Jarrett\RockPaperScissorsSpockLizardException;

class GameController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $round = new Round();

        $form = $this->createFormBuilder($round)
            ->add('move', ChoiceType::class, [
                'label' => 'Make A Move!',
                'label_attr' => ['class' => 'display-3'],
                'choices' => [
                    'Rock' => 'rock',
                    'Paper' => 'paper',
                    'Scissors' => 'scissors',
                    'Spock' => 'spock',
                    'Lizard' => 'lizard'
                ],
                'attr' => ['class' => 'display-4']
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Submit Move!',
                'attr' => ['class' => 'btn btn-lg btn-success'],
            ])
            ->getForm();

        $doctrine = $this->getDoctrine();
        $doctrineManager = $doctrine->getManager();

        $form->handleRequest($request);

        // handle player move
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            try {
                // create player
                $player = new Player('Human');
                $player->move($data->getMove());

                // create bot
                $bot = new Player('Bot');
                $bot->isBot(true);

                // setup game
                $game = new Game();
                $outcome = $game->addPlayers($player, $bot)
                    ->play()
                    ->getRoundOutcome();

                // get bot's move
                $bot_move = $bot->getLastMove();
                $data->setOpponentMove($bot_move);

                // lookup outcome
                if (!empty($outcome['ties'][0])) {
                    $tie = $outcome['ties'][0];
                    $data->setOutcome('tied');
                    $data->setOutcomeDescription($tie['description']);

                } else {
                    $player = $outcome['winners'][0]['player'];
                    $result = $player->getName() === 'Human' ? 'won' : 'lost';
                    $data->setOutcome($result);
                    $data->setOutcomeDescription($outcome['winners'][0]['description']);
                }

            } catch (RockPaperScissorsSpockLizardException $e) {
                // TODO display flash message
            }

            $doctrineManager->persist($data);
            $doctrineManager->flush();

            return $this->redirectToRoute('homepage');
        }

        $latest_rounds = $doctrine
            ->getRepository(Round::class)
            ->findBy([], ['createdAt' => 'desc']);

        $view_data = [
            'pageTitle' => 'Rock-Paper-Scissors-Spock-Lizard!',
            'form' => $form->createView(),
            'statistics' => $latest_rounds
        ];

        return $this->render('game/index.html.twig', $view_data);
    }
}
