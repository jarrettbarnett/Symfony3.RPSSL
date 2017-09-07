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
        $view_data['pageTitle'] = 'Rock-Paper-Scissors-Spock-Lizard!';

        // Entity and Repository
        $round = new Round();
        $doctrine = $this->getDoctrine();
        $doctrineManager = $doctrine->getManager();
        $roundRepository = $doctrineManager->getRepository('AppBundle:App\Round');

        // setup form
        $form = $this->createFormBuilder($round)
            ->add('move', ChoiceType::class, [
                'choices' => [
                    'Rock' => 'rock',
                    'Paper' => 'paper',
                    'Scissors' => 'scissors',
                    'Spock' => 'spock',
                    'Lizard' => 'lizard'
                ],
            ])
            ->add('submit', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        $notice = '';
        $notice_severity = 'primary';

        if (!$form->isSubmitted()) {
            $view_data['form'] = $form;
            return $this->render('game/index.html.twig', $view_data);
        }

        // handle player submission
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
    
                    $outcome_result = 'tied';
                    $outcome_description = $tie['description'];

                } else {
                    $winners = $outcome['winners'][0];
                    $player = $winners['player'];
                    $result = $player->getName() === 'Human' ? 'won' : 'lost';
                    $data->setOutcome($result);
                    $data->setOutcomeDescription($winners['description']);
                    
                    $outcome_result = $result;
                    $outcome_description = $winners['description'];
                }

            } catch (RockPaperScissorsSpockLizardException $e) {
                // catch game error so we can send it to the view
                $notice = $e->getMessage();
                $notice_severity = 'danger';
            } catch (\Exception $e) {
                // catch unknown error, log it, dont send it to the view
                $notice = 'What did you do? The game is broken...';
                $notice_severity = 'danger';

                // log the error to the application log
                $logger = $this->get('logger');
                $logger->err($e->getMessage());
            }

            $doctrineManager->persist($data);
            $doctrineManager->flush();
        }

        $latest_results = $roundRepository->findBy([], ['createdAt' => 'desc'], 10);
        $outcome_totals = $roundRepository->getOutcomeTotals();
        $player_totals = $roundRepository->getPlayerMovesTotals();
        $computer_totals = $roundRepository->getComputerMovesTotals();

        $view_data = [
            'pageTitle' => $view_data['pageTitle'],
            'form' => $form->createView(),
            'notice' => $notice,
            'notice_severity' => $notice_severity,
            'latest_results' => $latest_results,
            'outcome_totals' => $outcome_totals,
            'player_totals' => $player_totals,
            'computer_totals' => $computer_totals
        ];
        
        if (!empty($outcome_result))
        {
            $view_data['outcome_result'] = $outcome_result;
            $view_data['outcome_description'] = str_replace('Both', 'You both', $outcome_description);
        }

        return $this->render('game/index.html.twig', $view_data);
    }
}
