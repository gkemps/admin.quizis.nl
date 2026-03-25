<?php
namespace Quiz\Controller;

use DateTime;
use Quiz\Service\Finance as FinanceService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FinanceController extends AbstractActionController
{
    /** @var FinanceService */
    protected $financeService;

    public function __construct(FinanceService $financeService)
    {
        $this->financeService = $financeService;
    }

    public function indexAction()
    {
        $now     = new DateTime();
        $year    = (int)($this->getRequest()->getQuery('year',    $now->format('Y')));
        $quarter = (int)($this->getRequest()->getQuery('quarter', (int)ceil((int)$now->format('n') / 3)));

        $quarter = max(1, min(4, $quarter));

        // Type 1: factuur-quizzen (één totaalfactuur aan de klant)
        $factuurQuizzen    = $this->financeService->getQuizzesForQuarter($year, $quarter);
        $factuurFinancials = $this->financeService->calculateFinancials($factuurQuizzen);

        // Type 2: team-quizzen (teams betalen individueel) — betalingsdatum is leidend
        $bounds          = $this->financeService->getQuarterBoundsPublic($year, $quarter);
        $teamQuizzen     = $this->financeService->getTeamQuizzesForQuarter($year, $quarter);
        $teamFinancials  = $this->financeService->calculateTeamFinancials($teamQuizzen, $bounds['start'], $bounds['end']);

        $available = $this->financeService->getAvailableQuarters();

        $years = array_unique(array_map(function ($q) { return $q['year']; }, $available));
        rsort($years);

        if (empty($years)) {
            $years = [(int)$now->format('Y')];
        }

        // Gecombineerde BTW-totalen
        $totaalExclBtw  = $factuurFinancials['totaalOmzetExclBtw'] + $teamFinancials['totaalOmzetExclBtw'];
        $totaalBtw      = $factuurFinancials['totaalBtw']          + $teamFinancials['totaalBtw'];
        $totaalInclBtw  = $factuurFinancials['totaalOmzetInclBtw'] + $teamFinancials['totaalOmzetInclBtw'];

        return new ViewModel([
            'year'             => $year,
            'quarter'          => $quarter,
            'years'            => $years,
            'factuurFinancials'=> $factuurFinancials,
            'teamFinancials'   => $teamFinancials,
            'totaalExclBtw'    => $totaalExclBtw,
            'totaalBtw'        => $totaalBtw,
            'totaalInclBtw'    => $totaalInclBtw,
            'btwTarief'        => \Quiz\Service\Finance::BTW_TARIEF,
            'availableQuarters'=> $available,
        ]);
    }
}
