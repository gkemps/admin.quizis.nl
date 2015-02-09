<?php

namespace Quiz\Controller;

use LogicException;
use Zend\Form\FormInterface;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\Stdlib\Parameters;
use Zend\View\Model\ViewModel;

abstract class AbstractCrudController extends AbstractActionController
{
    const MESSAGE_PERSIST_SUCCESS    = 'messagePersistSuccess';
    const MESSAGE_PERSIST_FAILURE    = 'messagePersistFailure';
    const MESSAGE_VALIDATION_FAILURE = 'messageValidationFailure';
    const MESSAGE_PROCESSING_FAILURE = 'messageProcessingFailure';

    /**
     * Validation failure/success message template definitions
     *
     * @var array
     */
    protected $messageTemplates;

    public function __construct()
    {
        $this->messageTemplates = [
            self::MESSAGE_PERSIST_SUCCESS    => _('Item was successfully saved.'),
            self::MESSAGE_PERSIST_FAILURE    => _('Something went wrong while saving your data.'),
            self::MESSAGE_VALIDATION_FAILURE => _('Please verify if all fields were properly filled.'),
            self::MESSAGE_PROCESSING_FAILURE => _('Something went wrong while processing your data.'),
        ];
    }

    public function formAction()
    {
        /* @var $request \Zend\Http\PhpEnvironment\Request */
        $request = $this->getRequest();

        $form = $this->getStoredCrudForm();

        $viewModel = new ViewModel(
            [
                'form' => $form,
            ]
        );

        if ($request->isXmlHttpRequest()) {
            $viewModel->setTerminal(true);
        }

        return $viewModel;
    }

    /**
     * http://en.wikipedia.org/wiki/Post/Redirect/Get
     *
     * @return Response
     * @throws \LogicException
     */
    public function processAction()
    {
        /* @var $request \Zend\Http\PhpEnvironment\Request */
        $request = $this->getRequest();

        $form = $this->getCrudForm();
        $data = $request->getPost();

        $files = $request->getFiles();
        if (count($files) > 0) {
            foreach ($files as $key => $file) {
                $data->{$key} = $file;
            }
        }
        $form->setData($data);

        do {
            // If request parameters pass form validations
            if (!$form->isValid()) {
                $this->processFormValidationFailure($form);
                break;
            }

            try {
                if (!$this->processFormData($form)) {
                    $this->processFormProcessingFailure($form);
                    break;
                }
            } catch (\Exception $e) {
                error_log($e->getMessage());
                die($e->getMessage());

                $this->processFormProcessingFailure($form);
                break;
            }

            $this->flashMessenger()->addSuccessMessage($this->messageTemplates[self::MESSAGE_PERSIST_SUCCESS]);

            $response = $this->getCrudSuccessResponse();
            return $response;

        } while (false);

        return $this->sendFailureResponse();
    }

    /**
     * @param FormInterface $form
     */
    protected function processFormValidationFailure(FormInterface $form)
    {
        if (!empty($this->messageTemplates[self::MESSAGE_VALIDATION_FAILURE])) {
            $this->flashMessenger()->addErrorMessage($this->messageTemplates[self::MESSAGE_VALIDATION_FAILURE]);
        }

        /* @var $request \Zend\Http\PhpEnvironment\Request */
        $request = $this->getRequest();

        $this->storeCrudFormData($request->getPost());
    }

    /**
     * @param FormInterface $form
     */
    protected function processFormProcessingFailure(FormInterface $form)
    {
        if (!empty($this->messageTemplates[self::MESSAGE_PROCESSING_FAILURE])) {
            $this->flashMessenger()->addErrorMessage($this->messageTemplates[self::MESSAGE_PROCESSING_FAILURE]);
        }

        /* @var $request \Zend\Http\PhpEnvironment\Request */
        $request = $this->getRequest();

        $this->storeCrudFormData($request->getPost());
    }

    protected function sendFailureResponse()
    {
        $response = $this->getCrudFailureResponse();

        /* @var $request \Zend\Http\PhpEnvironment\Request */
        $request = $this->getRequest();

        if (!$request->isXmlHttpRequest()) {
            $response->setStatusCode(303);
        }

        return $response;
    }

    /**
     * @param FormInterface $form
     * @return mixed
     */
    abstract protected function processFormData(FormInterface $form);

    /**
     * Returns the response to send when the crud operation failed,
     * usually this will redirect back to the form
     *
     * @return Response
     */
    protected function getCrudFailureResponse()
    {
        return $this->redirect()->toRoute(
            $this->getEvent()->getRouteMatch()->getMatchedRouteName(),
            [
                'action' => 'form',
            ]
        );
    }

    /**
     * Returns the response to send when the crud operation succeeded,
     * usually this will redirect back to the overview page
     *
     * @return Response
     */
    protected function getCrudSuccessResponse()
    {
        /* @var $request \Zend\Http\PhpEnvironment\Request */
        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            /** @var Response $response */
            $response = $this->response;
            $response->setStatusCode(200);

            return $response;
        }

        return $this->redirect()->toRoute(
            $this->getEvent()->getRouteMatch()->getMatchedRouteName(),
            [
                'action' => 'index',
            ]
        );
    }

    /**
     * @return FormInterface
     */
    abstract protected function getCrudForm();

    /**
     * Checks if a form object exists in the session and returns it. If the form does not exist in the
     * session, it will return a new instance of the form.
     *
     * @throws \LogicException
     * @return FormInterface
     */
    protected function getStoredCrudForm()
    {
        $form = $this->getCrudForm();
        if (!$form instanceof FormInterface) {
            throw new LogicException(
                sprintf('%s::getCrudForm() should return instance of FormInterface, got %s', __CLASS__, get_class($form))
            );
        }

        // Fetch form from session if possible
        $session = $this->getSessionContainer();

        if ($session->offsetExists('form')) {
            $form->setData($session->offsetGet('form'));

            // validate form so errors are shown
            $form->isValid();

            $session->offsetUnset('form');
        }

        return $form;
    }

    /**
     * Stores $form in the session container
     *
     * @param Parameters $formData
     */
    protected function storeCrudFormData(Parameters $formData)
    {
        $session = $this->getSessionContainer();
        $session->offsetSet('form', $formData->toArray());
    }

    /**
     * Removes the form object from the session
     */
    protected function unsetStoredCrudForm()
    {
        $session = $this->getSessionContainer();
        $session->offsetUnset('form');
    }

    /**
     * Returns a session container that can be used for storing controller related stuff
     *
     * @return Container
     */
    protected function getSessionContainer()
    {
        return new Container(get_class($this));
    }
}

 