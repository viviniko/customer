<?php

namespace Viviniko\Customer\Services;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialUser;
use Viviniko\Customer\Events\SilenceRegistered;
use Viviniko\Customer\Repositories\Customer\CustomerRepository;
use Viviniko\Support\AbstractRequestRepositoryService;

class CustomerServiceImpl extends AbstractRequestRepositoryService implements CustomerService
{
    /**
     * @var \Viviniko\Customer\Repositories\Customer\CustomerRepository
     */
    protected $customerRepository;

    /**
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $events;

    /**
     * CustomerServiceImpl constructor.
     * @param \Viviniko\Customer\Repositories\Customer\CustomerRepository $customerRepository
     * @param \Illuminate\Contracts\Events\Dispatcher $events
     * @param \Illuminate\Http\Request
     */
    public function __construct(CustomerRepository $customerRepository, Dispatcher $events, Request $request)
    {
        parent::__construct($request);
        $this->customerRepository = $customerRepository;
        $this->events = $events;
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomer($id)
    {
        return $this->customerRepository->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerByEmail($email)
    {
        return $this->customerRepository->findByEmail($email);
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerBySocialId($provider, $providerId)
    {
        return $this->customerRepository->findBySocialId($provider, $providerId);
    }

    /**
     * {@inheritdoc}
     */
    public function associateSocialAccount($id, $provider, SocialUser $user)
    {
        return $this->customerRepository->associateSocialAccount($id, $provider, $user);
    }

    /**
     * {@inheritdoc}
     */
    public function updateSocialNetworks($id, array $data)
    {
        return $this->customerRepository->updateSocialNetworks($id, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function createCustomer(array $data)
    {
        return $this->customerRepository->create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function updateCustomer($id, $data)
    {
        if (empty($data['password'])) {
            unset($data['password']);
        }

        return $this->customerRepository->update($id, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function silenceRegister($data)
    {
        if (!isset($data['password'])) {
            $data['password'] = Str::random(8);
        }
        if (!isset($data['password_confirmation'])) {
            $data['password_confirmation'] = $data['password'];
        }

        $customer = $this->customerRepository->create($data);

        if ($customer) {
            $this->events->dispatch(new SilenceRegistered($customer, $data['password']));
        }

        return $customer;
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository()
    {
        return $this->customerRepository;
    }
}