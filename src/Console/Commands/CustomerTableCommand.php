<?php

namespace Viviniko\Customer\Console\Commands;

use Viviniko\Support\Console\CreateMigrationCommand;

class CustomerTableCommand extends CreateMigrationCommand
{
    /**
     * @var string
     */
    protected $name = 'customer:table';

    /**
     * @var string
     */
    protected $description = 'Create a migration for the customer service table';

    /**
     * @var string
     */
    protected $stub = __DIR__.'/stubs/customer.stub';

    /**
     * @var string
     */
    protected $migration = 'create_customer_table';
}
