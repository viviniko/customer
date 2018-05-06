<?php

namespace Viviniko\Customer\Services\Customer;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialUser;
use Viviniko\Customer\Contracts\CustomerService;
use Viviniko\Customer\Events\SilenceRegistered;
use Viviniko\Customer\Repositories\Customer\CustomerRepository;

class CustomerServiceImpl implements CustomerService
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
     */
    public function __construct(CustomerRepository $customerRepository, Dispatcher $events)
    {
        $this->customerRepository = $customerRepository;
        $this->events = $events;
    }

    /**
     * {@inheritdoc}
     */
    public function findByEmail($email)
    {
        return $this->customerRepository->findByEmail($email);
    }

    /**
     * {@inheritdoc}
     */
    public function findBySocialId($provider, $providerId)
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
    public function create(array $data)
    {
        return $this->customerRepository->create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function updatePassword($id, $password)
    {
        return $this->customerRepository->update($id, compact('password'));
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
}