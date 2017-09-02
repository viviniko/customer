<?php

namespace Viviniko\Customer\Services\Customer;

use Viviniko\Support\ValidatesData;
use Illuminate\Support\Facades\Config;

trait ValidatesCustomerData
{
    use ValidatesData;

    public function validateCreateData($data)
    {
        $this->validate($data, $this->rules());
    }

    public function validateUpdateData($userId, $data)
    {
        $rules = $this->rules($userId);
        unset($rules['password']);
        $this->validate($data, $rules);
    }

    public function rules($userId = null)
    {
        $userId = $userId ? (',' . $userId) : '';
        $table = Config::get('customer.customers_table');
        return [
            'email' => 'email|unique:' . $table . ',email' . $userId,
            'password' => 'required|min:6|confirmed',
        ];
    }
}