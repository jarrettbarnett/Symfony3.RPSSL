<?php
namespace AppBundle\Repository\App;

use AppBundle\Entity\App\Round;
use Doctrine\ORM\EntityRepository;
use Jarrett\RockPaperScissorsSpockLizard\Game;
use Jarrett\RockPaperScissorsSpockLizard\Player;
use Jarrett\RockPaperScissorsSpockLizardException;

/**
 * RoundRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RoundRepository extends EntityRepository
{
    /**
     * @param $data
     * @return bool true on success
     */
    public function makeMove($data)
    {
        return true;
    }
    
    /**
     * Get Totals For Column Values
     * @param $column
     * @return mixed
     */
    public function getTotalsForColumnValues($column)
    {
        $sql = 'SELECT DISTINCT('.$column.'), count(*) AS total FROM app_round GROUP BY ' . $column . ' ORDER BY total desc';
    
        $results = $this->getEntityManager()->getConnection()->executeQuery($sql)->fetchAll();
    
        foreach ($results as $result)
        {
            $data[$result[$column]] = $result['total'];
        }
        
        return $data;
    }
    
    /**
     * Get Outcome Totals
     * @return array
     */
    public function getOutcomeTotals()
    {
        return $this->getTotalsForColumnValues('outcome');
    }
    
    /**
     * Player Moves Totals
     */
    public function getPlayerMovesTotals()
    {
        return $this->getTotalsForColumnValues('move');
    }
    
    /**
     * Computer Moves Totals
     */
    public function getComputerMovesTotals()
    {
        return $this->getTotalsForColumnValues('opponent_move');
    }

    /**
     * List
     * @return mixed
     */
    public function listAction()
    {
        return $this->getDoctrine()
            ->getRepository(Round::class)
            ->findAll();
    }
}
