<?php

namespace Viviniko\Customer\Events;

use Illuminate\Auth\Events\Registered;

class SilenceRegistered extends Registered
{
    public $password;

    public function __construct($user, $password)
    {
        parent::__construct($user);
        $this->password = $password;
    }
}