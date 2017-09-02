<?php

namespace Viviniko\Customer\Listeners;

use Carbon\Carbon;
use Viviniko\Customer\Contracts\CustomerService;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class CustomerEventSubscriber
{
	/**
	 * @var \Viviniko\Customer\Contracts\CustomerService
	 */
	private $customerService;

	public function __construct(CustomerService $customerService)
    {
		$this->customerService = $customerService;
	}

    public function onRegistered(Registered $event)
    {
        $this->customerService->update($event->user->id, [
            'reg_ip' => Request::ip(),
        ]);
    }

	public function onLogin(Login $event)
    {
	    $this->customerService->update($event->user->id, [
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
