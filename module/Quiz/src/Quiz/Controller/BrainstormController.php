<?php
namespace Quiz\Controller;

use Quiz\Service\BrainstormService;
use Quiz\Form\Brainstorm as BrainstormForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class BrainstormController extends AbstractActionController
{
    /** @var BrainstormService */
    protected $brainstormService;

    /** @var BrainstormForm */
    protected $brainstormForm;

    /**
     * @param BrainstormService $brainstormService
     * @param BrainstormForm $brainstormForm
     */
    public function __construct(
        BrainstormService $brainstormService,
        BrainstormForm $brainstormForm
    ) {
        $this->brainstormService = $brainstormService;
        $this->brainstormForm = $brainstormForm;
    }

    /**
     * Display the brainstorm form
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        return new ViewModel([
            'form' => $this->brainstormForm,
        ]);
    }

    /**
     * Process the form and generate questions
     *
     * @return ViewModel
     */
    public function processAction()
    {
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return $this->redirect()->toRoute('brainstorm');
        }

        $this->brainstormForm->setData($request->getPost());

        if (!$this->brainstormForm->isValid()) {
            // Form validation failed, redirect back with error
            $this->flashMessenger()->addErrorMessage('Ongeldige URL. Controleer de invoer en probeer opnieuw.');
            return $this->redirect()->toRoute('brainstorm');
        }

        $data = $this->brainstormForm->getData();
        $url = $data[BrainstormForm::ELEM_URL];
        $numQuestions = isset($data[BrainstormForm::ELEM_NUM_QUESTIONS]) ? (int)$data[BrainstormForm::ELEM_NUM_QUESTIONS] : 5;

        try {
            // Generate questions using the service
            $questions = $this->brainstormService->generateQuestions($url, $numQuestions);

            // Display results
            return new ViewModel([
                'questions' => $questions,
                'sourceUrl' => $url,
            ]);
        } catch (\Exception $e) {
            // API call failed, redirect back with error message
            $this->flashMessenger()->addErrorMessage(
                'Fout bij het genereren van vragen: ' . $e->getMessage()
            );
            return $this->redirect()->toRoute('brainstorm');
        }
    }
}
