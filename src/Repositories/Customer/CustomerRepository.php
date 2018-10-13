<?php

namespace Viviniko\Customer\Repositories\Customer;

use Laravel\Socialite\Contracts\User as SocialUser;
use Viviniko\Repository\SearchRequest;

interface CustomerRepository
{
    /**
     * Search.
     *
     * @param SearchRequest $searchRequest
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function search(SearchRequest $searchRequest);

    /**
     * Find customer by its id.
     *
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * Get customer by email.
     *
     * @param $email
     * @return mixed
     */
    public function findByEmail($email);

    /**
     * Find user registered via social network.
     *
     * @param $provider Provider used for authentication.
     * @param $providerId Provider's unique identifier for authenticated user.
     * @return mixed
     */
    public function findBySocialId($provider, $providerId);

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
     * Create new customer.
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * Update customer specified by it's id.
     *
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function update($id, array $data);

    /**
     * Delete customer with provided id.
     *
     * @param $id
     * @return mixed
     */
    public function delete($id);
}