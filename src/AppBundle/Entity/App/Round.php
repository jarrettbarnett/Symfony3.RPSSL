<?php

namespace AppBundle\Entity\App;

use Doctrine\ORM\Mapping as ORM;

/**
 * Round
 *
 * @ORM\Table(name="app_round")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\App\RoundRepository")
 */
class Round
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="move", type="string", length=10)
     */
    private $move;

    /**
     * @var string
     *
     * @ORM\Column(name="opponent_move", type="string", length=10)
     */
    private $opponentMove;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetimetz")
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="outcome", type="string", length=5)
     */
    private $outcome;

    /**
     * @var string
     *
     * @ORM\Column(name="outcome_description", type="string", length=255)
     */
    private $outcomeDescription;

    /**
     * Round constructor.
     */
    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set move
     *
     * @param string $move
     *
     * @return Round
     */
    public function setMove($move)
    {
        $this->move = $move;

        return $this;
    }

    /**
     * Get move
     *
     * @return string
     */
    public function getMove()
    {
        return $this->move;
    }

    /**
     * Set opponentMove
     *
     * @param string $opponentMove
     *
     * @return Round
     */
    public function setOpponentMove($opponentMove)
    {
        $this->opponentMove = $opponentMove;

        return $this;
    }

    /**
     * Get opponentMove
     *
     * @return string
     */
    public function getOpponentMove()
    {
        return $this->opponentMove;
    }

    /**
     * Set datetime
     *
     * @param \DateTime $created_at
     *
     * @return Round
     */
    public function setCreatedAt($created_at)
    {
        $this->createdAt = $created_at;

        return $this;
    }

    /**
     * Get datetime
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set outcome
     *
     * @param string $outcome
     *
     * @return Round
     */
    public function setOutcome($outcome)
    {
        $this->outcome = $outcome;

        return $this;
    }

    /**
     * Get outcome
     *
     * @return string
     */
    public function getOutcome()
    {
        return $this->outcome;
    }

    /**
     * Set outcomeDescription
     *
     * @param string $outcomeDescription
     *
     * @return Round
     */
    public function setOutcomeDescription($outcomeDescription)
    {
        $this->outcomeDescription = $outcomeDescription;

        return $this;
    }

    /**
     * Get outcomeDescription
     *
     * @return string
     */
    public function getOutcomeDescription()
    {
        return $this->outcomeDescription;
    }
}

