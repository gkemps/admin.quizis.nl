<?php
namespace Quiz\Service;

use DateTime;
use Zend\Authentication\AuthenticationService;
use Doctrine\ORM\EntityManager;
use Quiz\Entity\QuizLog as QuizLogEntity;
use Quiz\Entity\QuizRoundQuestion as QuizRoundQuestionEntity;
use Quiz\Entity\ThemeRound as ThemeRoundEntity;
use Quiz\Entity\QuizRound as QuizRoundEntity;

class QuizLog extends AbstractService
{
    protected $authenticationService;

    /**
     * @param EntityManager $em
     * @param AuthenticationService $authenticationService
     */
    public function __construct(
        EntityManager $em,
        AuthenticationService $authenticationService
    ) {
        parent::__construct($em);

        $this->authenticationService = $authenticationService;
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getRepository()
    {
        return $this->em->getRepository('Quiz\Entity\QuizLog');
    }

    /**
     * @param QuizLogEntity $quizLog
     * @return QuizLogEntity
     */
    public function createNewQuizLog(QuizLogEntity $quizLog)
    {
        $user = $this->authenticationService->getIdentity();

        $quizLog->setUser($user);
        $quizLog->setDateCreated(new DateTime('now'));
        $this->persist($quizLog);

        return $quizLog;
    }

    /**
     * @param QuizRoundQuestionEntity $quizRoundQuestionEntity
     * @return \Quiz\Entity\QuizLog
     */
    public function createNewQuestionRemovedLog(QuizRoundQuestionEntity $quizRoundQuestion)
    {
        /** @var \Quiz\Entity\User $user */
        $user = $this->authenticationService->getIdentity();

        $text = "Vraag ".($quizRoundQuestion->getQuestionNumber() / 10)." uit ronde ".$quizRoundQuestion->getQuizRound()->getNumber()." verwijderd door ".$user->getDisplayName()." (".$quizRoundQuestion->getQuestion()->getQuestion().")";

        $quizLog = new QuizLogEntity();
        $quizLog->setQuiz($quizRoundQuestion->getQuizRound()->getQuiz());
        $quizLog->setText($text);
        $quizLog->setIcon('fa fa-reply');

        return $this->createNewQuizLog($quizLog);
    }

    public function createNewQuestionAddedLog(QuizRoundQuestionEntity $quizRoundQuestion)
    {
        /** @var \Quiz\Entity\User $user */
        $user = $this->authenticationService->getIdentity();

        $text = "Vraag toegevoegd aan ronde ".$quizRoundQuestion->getQuizRound()->getNumber()." door ".$user->getDisplayName()." (".$quizRoundQuestion->getQuestion()->getQuestion().")";

        $quizLog = new QuizLogEntity();
        $quizLog->setQuiz($quizRoundQuestion->getQuizRound()->getQuiz());
        $quizLog->setText($text);
        $quizLog->setIcon('fa fa-mail-forward');

        return $this->createNewQuizLog($quizLog);
    }

    public function createNewQuestionChangedLog(QuizRoundQuestionEntity $quizRoundQuestion)
    {
        /** @var \Quiz\Entity\User $user */
        $user = $this->authenticationService->getIdentity();

        $text = "Vraag verplaatst naar positie ".($quizRoundQuestion->getQuestionNumber() / 10)." in ronde ".$quizRoundQuestion->getQuizRound()->getNumber()." door ".$user->getDisplayName()." (".$quizRoundQuestion->getQuestion()->getQuestion().")";

        $quizLog = new QuizLogEntity();
        $quizLog->setQuiz($quizRoundQuestion->getQuizRound()->getQuiz());
        $quizLog->setText($text);
        $quizLog->setIcon('fa fa-exchange');

        return $this->createNewQuizLog($quizLog);
    }

    public function createThemeRoundAddedLog(ThemeRoundEntity $themeRound, QuizRoundEntity $quizRound)
    {
        /** @var \Quiz\Entity\User $user */
        $user = $this->authenticationService->getIdentity();

        $text = "Thema ronde ".$themeRound->getName()." toegevoegd aan ronde ".$quizRound->getNumber()." door ".$user->getDisplayName();

        $quizLog = new QuizLogEntity();
        $quizLog->setQuiz($quizRound->getQuiz());
        $quizLog->setText($text);
        $quizLog->setIcon('fa fa-comment');

        return $this->createNewQuizLog($quizLog);
    }
}
