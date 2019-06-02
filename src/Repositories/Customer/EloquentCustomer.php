<?php

namespace Viviniko\Customer\Repositories\Customer;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Contracts\User as SocialUser;
use Viviniko\Repository\EloquentRepository;

class EloquentCustomer extends EloquentRepository implements CustomerRepository
{
    public function __construct()
    {
        parent::__construct(Config::get('customer.customer'));
    }

    /**
     * {@inheritdoc}
     */
    public function findBySocialId($provider, $providerId)
    {
        $customerTable = Config::get('customer.customers_table');
        $socialTable = Config::get('customer.social_logins_table');

        return $this->createModel()->newQuery()->leftJoin($socialTable, "{$customerTable}.id", '=', "{$socialTable}.customer_id")
            ->select("{$customerTable}.*")
            ->where("{$socialTable}.provider", $provider)
            ->where("{$socialTable}.provider_id", $providerId)
            ->first();
    }

    /**
     * {@inheritdoc}
     */
    public function associateSocialAccount($id, $provider, SocialUser $user)
    {
        return DB::table(Config::get('customer.social_logins_table'))->insert([
            'customer_id' => $id,
            'provider' => $provider,
            'provider_id' => $user->getId(),
            'avatar' => $user->getAvatar(),
            'created_at' => Carbon::now(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function updateSocialNetworks($id, array $data)
    {
        return $this->find($id)->socialNetworks()->updateOrCreate([], $data);
    }
}