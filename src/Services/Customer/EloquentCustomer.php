<?php

namespace Viviniko\Customer\Services\Customer;

use Carbon\Carbon;
use Viviniko\Customer\Contracts\CustomerService as CustomerServiceInterface;
use Viviniko\Customer\Contracts\Provider;
use Viviniko\Repository\SimpleRepository;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialUser;

class EloquentCustomer extends SimpleRepository implements CustomerServiceInterface
{
    use ValidatesCustomerData;

    protected $modelConfigKey = 'customer.customer';

    /**
     * {@inheritdoc}
     */
    public function findByEmail($email)
    {
        return $this->findBy('email', $email)->first();
    }

    /**
     * Silence register.
     *
     * @param $data
     * @return mixed
     */
    public function silenceRegister($data)
    {
        if (!isset($data['password'])) {
            $data['password'] = Str::random(8);
        }
        if (!isset($data['password_confirmation'])) {
            $data['password_confirmation'] = $data['password'];
        }

        return $this->create($data);
    }

    /**
     * Find user registered via social network.
     *
     * @param $provider Provider used for authentication.
     * @param $providerId Provider's unique identifier for authenticated user.
     * @return mixed
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
     * Associate account details returned from social network
     * to user with provided customer id.
     *
     * @param $id
     * @param $provider
     * @param SocialUser $user
     * @return mixed
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
     * Update customer social networks.
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function updateSocialNetworks($id, array $data)
    {
        return $this->find($id)->socialNetworks()->updateOrCreate([], $data);
    }
}