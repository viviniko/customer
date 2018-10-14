<?php

namespace Viviniko\Customer\Services;

use Laravel\Socialite\Contracts\User as SocialUser;

interface CustomerService
{
    /**
     * Paginate the given query into a simple paginator.
     *
     * @param $pageSize
     * @param array $wheres
     * @param array $orders
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($pageSize, $wheres = [], $orders = []);

    /**
     * Get customer
     *
     * @param $id
     * @return mixed
     */
    public function getCustomer($id);

    /**
     * Get customer by email.
     *
     * @param $email
     * @return mixed
     */
    public function getCustomerByEmail($email);

    /**
     * Find user registered via social network.
     *
     * @param $provider Provider used for authentication.
     * @param $providerId Provider's unique identifier for authenticated user.
     * @return mixed
     */
    public function getCustomerBySocialId($provider, $providerId);

    /**
     * Associate account details returned from social network
     * to user with provided customer id.
     *
     * @param $id
     * @param $provider
     * @param SocialUser $user
     * @return mixed
     */
    public function associateSocialAccount($id, $provider, SocialUser $user);

    /**
     * Update customer social networks.
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function updateSocialNetworks($id, array $data);

    /**
     * create customer.
     *
     * @param array $data
     * @return mixed
     */
    public function createCustomer(array $data);

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function updateCustomer($id, $data);

    /**
     * Silence register.
     *
     * @param $data
     * @return mixed
     */
    public function silenceRegister($data);
}