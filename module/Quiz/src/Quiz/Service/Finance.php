<?php
namespace Quiz\Service;

use DateTime;
use Doctrine\ORM\EntityManager;
use Quiz\Entity\Quiz as QuizEntity;

class Finance extends AbstractService
{
    const BTW_TARIEF = 0.21;

    /** @var \Doctrine\ORM\EntityRepository */
    protected $quizRepository;

    public function __construct(EntityManager $em)
    {
        parent::__construct($em);
        $this->quizRepository = $this->em->getRepository('Quiz\Entity\Quiz');
    }

    protected function getRepository()
    {
        return $this->quizRepository;
    }

    /**
     * Hulpfunctie: bepaal kwartaal-grenzen als DateTime-objecten.
     *
     * @param int $year
     * @param int $quarter  1–4
     * @return array  ['start' => DateTime, 'end' => DateTime]
     */
    private function getQuarterBounds($year, $quarter)
    {
        $quarter    = max(1, min(4, (int)$quarter));
        $year       = (int)$year;
        $startMonth = (($quarter - 1) * 3) + 1;
        $endMonth   = $startMonth + 2;

        $start = new DateTime(sprintf('%d-%02d-01 00:00:00', $year, $startMonth));
        $end   = new DateTime(sprintf('%d-%02d-01 00:00:00', $year, $endMonth));
        $end->modify('last day of this month')->setTime(23, 59, 59);

        return ['start' => $start, 'end' => $end];
    }

    /**
     * Geeft alle kwartalen waarvoor gefactureerde quizzen of team-quizzen bestaan.
     *
     * @return array  [['year' => int, 'quarter' => int], ...]  gesorteerd aflopend
     */
    public function getAvailableQuarters()
    {
        $quarters = [];

        // Factuur-quizzen: gefilterd op dateInvoiced
        $qb = $this->em->createQueryBuilder();
        $qb->select('q.dateInvoiced AS d')
            ->from('Quiz\Entity\Quiz', 'q')
            ->where('q.dateInvoiced IS NOT NULL');
        foreach ($qb->getQuery()->getResult() as $row) {
            $this->addQuarterFromDate($quarters, $row['d']);
        }

        // Team-quizzen: gefilterd op betalingsdatum van teams (datePaid en datePaidCash)
        $qb2 = $this->em->createQueryBuilder();
        $qb2->select('t.datePaid AS dp, t.datePaidCash AS dpc')
            ->from('Quiz\Entity\Team', 't')
            ->innerJoin('t.quiz', 'q')
            ->where('q.dateInvoiced IS NULL')
            ->andWhere('t.canceled = 0 OR t.canceled IS NULL')
            ->andWhere('t.datePaid IS NOT NULL OR t.datePaidCash IS NOT NULL');
        foreach ($qb2->getQuery()->getResult() as $row) {
            if ($row['dp'] !== null) {
                $this->addQuarterFromDate($quarters, $row['dp']);
            }
            if ($row['dpc'] !== null) {
                $this->addQuarterFromDate($quarters, $row['dpc']);
            }
        }

        // Sorteer aflopend
        usort($quarters, function ($a, $b) {
            if ($a['year'] !== $b['year']) return $b['year'] - $a['year'];
            return $b['quarter'] - $a['quarter'];
        });

        return $quarters;
    }

    private function addQuarterFromDate(array &$quarters, \DateTime $date)
    {
        $year    = (int)$date->format('Y');
        $month   = (int)$date->format('n');
        $quarter = (int)ceil($month / 3);
        $key     = $year . 'Q' . $quarter;
        if (!isset($quarters[$key])) {
            $quarters[$key] = ['year' => $year, 'quarter' => $quarter];
        }
    }

    /**
     * Geeft factuur-quizzen (met dateInvoiced) in het opgegeven kwartaal.
     *
     * @param int $year
     * @param int $quarter  1–4
     * @return QuizEntity[]
     */
    public function getQuizzesForQuarter($year, $quarter)
    {
        $bounds = $this->getQuarterBounds($year, $quarter);

        $qb = $this->em->createQueryBuilder();
        $qb->select('q')
            ->from('Quiz\Entity\Quiz', 'q')
            ->leftJoin('q.customer', 'c')
            ->where('q.dateInvoiced IS NOT NULL')
            ->andWhere('q.dateInvoiced >= :start')
            ->andWhere('q.dateInvoiced <= :end')
            ->setParameter('start', $bounds['start'])
            ->setParameter('end', $bounds['end'])
            ->orderBy('q.dateInvoiced', 'ASC');

        return $qb->getQuery()->getResult();
    }

    /**
     * Geeft team-quizzen (teams betalen individueel, geen overall factuur) waarbij
     * minimaal één team een betalingsdatum heeft die in het opgegeven kwartaal valt.
     *
     * @param int $year
     * @param int $quarter  1–4
     * @return QuizEntity[]
     */
    public function getTeamQuizzesForQuarter($year, $quarter)
    {
        $bounds = $this->getQuarterBounds($year, $quarter);

        $qb = $this->em->createQueryBuilder();
        $qb->select('q')
            ->from('Quiz\Entity\Quiz', 'q')
            ->innerJoin('q.teams', 't')
            ->where('q.dateInvoiced IS NULL')
            ->andWhere('t.canceled = 0 OR t.canceled IS NULL')
            ->andWhere(
                '(t.datePaid >= :start AND t.datePaid <= :end)'
                . ' OR (t.datePaidCash >= :start AND t.datePaidCash <= :end)'
            )
            ->setParameter('start', $bounds['start'])
            ->setParameter('end', $bounds['end'])
            ->groupBy('q.id')
            ->orderBy('q.date', 'ASC');

        return $qb->getQuery()->getResult();
    }

    /**
     * Geeft de kwartaalgrenzen terug als array voor gebruik in de controller.
     *
     * @param int $year
     * @param int $quarter
     * @return array  ['start' => DateTime, 'end' => DateTime]
     */
    public function getQuarterBoundsPublic($year, $quarter)
    {
        return $this->getQuarterBounds($year, $quarter);
    }

    /**
     * Berekent financieel overzicht voor factuur-quizzen.
     * priceInvoice wordt beschouwd als excl. BTW (B2B factuur).
     *
     * @param QuizEntity[] $quizzes
     * @return array
     */
    public function calculateFinancials(array $quizzes)
    {
        $rows = [];
        $totaalOmzetExclBtw = 0.0;
        $totaalBtw          = 0.0;
        $totaalOmzetInclBtw = 0.0;
        $totaalKorting      = 0.0;

        foreach ($quizzes as $quiz) {
            $brutoPrijs = (float)($quiz->getPriceInvoice() ?? 0);

            $kortingBedrag = 0.0;
            if ($quiz->getDiscountAmount() > 0) {
                $kortingBedrag = (float)$quiz->getDiscountAmount();
            } elseif ($quiz->getDiscountPercentage() > 0) {
                $kortingBedrag = $brutoPrijs * ((float)$quiz->getDiscountPercentage() / 100);
            }

            $nettoExclBtw = $brutoPrijs - $kortingBedrag;
            $btwBedrag    = $nettoExclBtw * self::BTW_TARIEF;
            $inclBtw      = $nettoExclBtw + $btwBedrag;

            $totaalOmzetExclBtw += $nettoExclBtw;
            $totaalBtw          += $btwBedrag;
            $totaalOmzetInclBtw += $inclBtw;
            $totaalKorting      += $kortingBedrag;

            $rows[] = [
                'quiz'         => $quiz,
                'brutoPrijs'   => $brutoPrijs,
                'korting'      => $kortingBedrag,
                'nettoExclBtw' => $nettoExclBtw,
                'btw'          => $btwBedrag,
                'inclBtw'      => $inclBtw,
            ];
        }

        return [
            'rows'               => $rows,
            'totaalOmzetExclBtw' => $totaalOmzetExclBtw,
            'totaalBtw'          => $totaalBtw,
            'totaalOmzetInclBtw' => $totaalOmzetInclBtw,
            'totaalKorting'      => $totaalKorting,
        ];
    }

    /**
     * Berekent financieel overzicht voor team-quizzen.
     * team.amount is het bedrag dat een team betaald heeft (incl. BTW).
     * BTW wordt teruggerekend: excl. BTW = inclBtw / (1 + BTW_TARIEF).
     *
     * Alleen teams waarvan de betalingsdatum (datePaid of datePaidCash) binnen
     * [$start, $end] valt worden meegeteld — zodat per kwartaal alleen de
     * daadwerkelijk in dat kwartaal ontvangen betalingen worden gerapporteerd.
     *
     * @param QuizEntity[] $quizzes
     * @param DateTime     $start
     * @param DateTime     $end
     * @return array
     */
    public function calculateTeamFinancials(array $quizzes, DateTime $start, DateTime $end)
    {
        $rows = [];
        $totaalOmzetExclBtw = 0.0;
        $totaalBtw          = 0.0;
        $totaalOmzetInclBtw = 0.0;

        foreach ($quizzes as $quiz) {
            $teamRows          = [];
            $quizInclBtw       = 0.0;
            $aantalBetaald     = 0;
            $aantalGeannuleerd = 0;

            foreach ($quiz->getTeams() as $team) {
                if ($team->isCanceled()) {
                    $aantalGeannuleerd++;
                    continue;
                }

                // Bepaal de effectieve betalingsdatum die in dit kwartaal valt
                $betaaldInKwartaal = false;
                $betaalDatum = null;

                if ($team->getDatePaid() !== null) {
                    $dp = $team->getDatePaid();
                    if ($dp >= $start && $dp <= $end) {
                        $betaaldInKwartaal = true;
                        $betaalDatum = $dp;
                    }
                }

                if (!$betaaldInKwartaal && $team->getDatePaidCash() !== null) {
                    $dpc = $team->getDatePaidCash();
                    if ($dpc >= $start && $dpc <= $end) {
                        $betaaldInKwartaal = true;
                        $betaalDatum = $dpc;
                    }
                }

                if (!$betaaldInKwartaal) {
                    continue;
                }

                $teamBedragIncl = (float)($team->getAmount() ?? 0);
                $teamBedragExcl = $teamBedragIncl / (1 + self::BTW_TARIEF);
                $teamBtw        = $teamBedragIncl - $teamBedragExcl;

                $quizInclBtw += $teamBedragIncl;
                $aantalBetaald++;

                $teamRows[] = [
                    'team'         => $team,
                    'betaalDatum'  => $betaalDatum,
                    'inclBtw'      => $teamBedragIncl,
                    'nettoExclBtw' => $teamBedragExcl,
                    'btw'          => $teamBtw,
                ];
            }

            if ($aantalBetaald === 0 && $aantalGeannuleerd === 0) {
                continue;
            }

            $quizExclBtw = $quizInclBtw > 0 ? $quizInclBtw / (1 + self::BTW_TARIEF) : 0.0;
            $quizBtw     = $quizInclBtw - $quizExclBtw;

            $totaalOmzetInclBtw += $quizInclBtw;
            $totaalOmzetExclBtw += $quizExclBtw;
            $totaalBtw          += $quizBtw;

            $rows[] = [
                'quiz'             => $quiz,
                'teamRows'         => $teamRows,
                'aantalBetaald'    => $aantalBetaald,
                'aantalGeannuleerd'=> $aantalGeannuleerd,
                'inclBtw'          => $quizInclBtw,
                'nettoExclBtw'     => $quizExclBtw,
                'btw'              => $quizBtw,
            ];
        }

        return [
            'rows'               => $rows,
            'totaalOmzetExclBtw' => $totaalOmzetExclBtw,
            'totaalBtw'          => $totaalBtw,
            'totaalOmzetInclBtw' => $totaalOmzetInclBtw,
        ];
    }
}
