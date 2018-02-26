<?php

namespace Viviniko\Customer\Listeners;

use Carbon\Carbon;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Viviniko\Customer\Repositories\Customer\CustomerRepository;

class CustomerEventSubscriber
{
	/**
	 * @var \Viviniko\Customer\Repositories\Customer\CustomerRepository
	 */
	private $customers;

	public function __construct(CustomerRepository $customers)
    {
		$this->customers = $customers;
	}

    public function onRegistered(Registered $event)
    {
        $this->customers->update($event->user->id, [
            'reg_ip' => Request::ip(),
        ]);
    }

	public function onLogin(Login $event)
    {
	    $this->customers->update($event->user->id, [
            'log_num' => DB::raw('log_num + 1'),
            'log_ip' => Request::ip(),
            'log_date' => Carbon::now(),
        ]);
	}

	/**
	 * Register the listeners for the subscriber.
	 *
	 * @param  \Illuminate\Events\Dispatcher  $events
	 */
	public function subscribe($events)
    {
		$events->listen(Login::class, 'Viviniko\Customer\Listeners\CustomerEventSubscriber@onLogin');
        $events->listen(Registered::class, 'Viviniko\Customer\Listeners\CustomerEventSubscriber@onRegistered');
	}
}
