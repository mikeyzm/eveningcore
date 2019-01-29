<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Convert extends Model
{
    function options()
    {
        return $this->hasMany(ConvertOption::class);
    }
}
