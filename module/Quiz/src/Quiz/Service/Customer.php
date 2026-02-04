<?php
namespace Quiz\Service;

use DateTime;
use Quiz\Entity\Customer as CustomerEntity;
use Doctrine\ORM\EntityManager;
use Zend\Authentication\AuthenticationService;

class Customer
{
    /** @var EntityManager  */
    protected $em;

    /** @var \Doctrine\ORM\EntityRepository  */
    protected $customerRepository;

    /** @var AuthenticationService  */
    protected $authenticationService;

    /**
     * @param EntityManager $em
     * @param AuthenticationService $authenticationService
     */
    public function __construct(
        EntityManager $em,
        AuthenticationService $authenticationService
    ) {
        $this->em = $em;
        $this->customerRepository = $this->em->getRepository('Quiz\Entity\Customer');
        $this->authenticationService = $authenticationService;
    }

    /**
     * @param $id
     * @return null|CustomerEntity
     */
    public function getCustomerById($id)
    {
        return $this->customerRepository->find($id);
    }

    /**
     * @return CustomerEntity[]
     */
    public function getAllCustomers()
    {
        return $this->customerRepository->findBy([], ['name' => 'ASC']);
    }

    /**
     * @param CustomerEntity $customer
     * @return CustomerEntity
     */
    public function createNewCustomer(CustomerEntity $customer)
    {
        $user = $this->authenticationService->getIdentity();

        $customer->setDateCreated(new DateTime('now'));
        $customer->setCreatedBy($user);

        $this->persist($customer);

        return $customer;
    }

    /**
     * @param CustomerEntity $customer
     * @return CustomerEntity
     */
    public function updateCustomer(CustomerEntity $customer)
    {
        $customer->setDateUpdated(new DateTime('now'));

        $this->persist($customer);

        return $customer;
    }

    /**
     * @param CustomerEntity $customer
     */
    protected function persist(CustomerEntity $customer)
    {
        $this->em->persist($customer);
        $this->em->flush();
    }
}
