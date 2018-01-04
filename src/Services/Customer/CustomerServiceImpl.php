<?php

namespace Viviniko\Customer\Services\Customer;

use Viviniko\Customer\Contracts\CustomerService;
use Viviniko\Customer\Repositories\Customer\CustomerRepository;

class CustomerServiceImpl implements CustomerService
{
    protected $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }
}