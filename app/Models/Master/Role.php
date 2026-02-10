<?php

namespace App\Models\Master;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'master';
}
