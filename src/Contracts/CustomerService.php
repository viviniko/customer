<?php

namespace Viviniko\Customer\Contracts;

use Laravel\Socialite\Contracts\User as SocialUser;

interface CustomerService
{
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
     * create customer.
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * update password.
     *
     * @param $id
     * @param string $password
     * @return mixed
     */
    public function updatePassword($id, $password);

    /**
     * Silence register.
     *
     * @param $data
     * @return mixed
     */
    public function silenceRegister($data);
}