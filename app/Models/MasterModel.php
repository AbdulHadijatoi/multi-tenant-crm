<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class MasterModel extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'master';
}
