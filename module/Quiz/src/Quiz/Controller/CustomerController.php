<?php
namespace Quiz\Controller;

use Quiz\Service\Customer as CustomerService;
use Quiz\Entity\Customer as CustomerEntity;
use Quiz\Form\Customer as CustomerForm;
use Zend\Form\FormInterface;
use Zend\View\Model\ViewModel;

class CustomerController extends AbstractCrudController
{
    /** @var CustomerService  */
    protected $customerService;

    /** @var CustomerForm  */
    protected $customerForm;

    /**
     * @param CustomerService $customerService
     * @param CustomerForm $customerForm
     */
    public function __construct(
        CustomerService $customerService,
        CustomerForm $customerForm
    ) {
        parent::__construct();

        $this->customerService = $customerService;
        $this->customerForm = $customerForm;
    }

    public function indexAction()
    {
        $customers = $this->customerService->getAllCustomers();

        return new ViewModel(
            [
                'customers' => $customers
            ]
        );
    }

    /**
     * @param FormInterface $form
     * @return mixed
     */
    protected function processFormData(FormInterface $form)
    {
        /** @var \Quiz\Entity\Customer $customer */
        $customer = $form->getObject();

        if (!$customer->getId()) {
            $this->customerService->createNewCustomer($customer);
        } else {
            $this->customerService->updateCustomer($customer);
        }

        return true;
    }

    /**
     * @return FormInterface
     */
    protected function getCrudForm()
    {
        $customerId = $this->getRequest()->getQuery('id');

        if (empty($customerId)) {
            $customer = new CustomerEntity();
        } else {
            $customer = $this->customerService->getCustomerById($customerId);
        }

        $this->customerForm->bind($customer);

        return $this->customerForm;
    }

    protected function getCrudSuccessResponse()
    {
        return $this->redirect()->toRoute('customer');
    }

    protected function getCrudFailureResponse()
    {
        return $this->redirect()->toRoute('customer');
    }
}
