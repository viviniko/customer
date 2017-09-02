<?php

namespace Viviniko\Customer\Models;

use Viviniko\Support\Database\Eloquent\Model;

class CustomerSocialNetworks extends Model
{
    protected $tableConfigKey = 'customer.social_networks_table';

	public $timestamps = false;

	protected $fillable = ['facebook', 'twitter', 'google_plus', 'dribbble', 'linked_in', 'skype'];
}
