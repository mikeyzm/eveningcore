<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Convert extends Model
{
    protected $guarded = [];

    protected $dates = ['expired_at'];

    protected $with = ['options'];

    function options()
    {
        return $this->hasMany(ConvertOption::class);
    }

    function getOption($name)
    {
        return optional($this->options->where('name', $name)->first())->value;
    }

    function getUrlAttribute()
    {
        return \Storage::disk('public')->url('converts/' . $this->file_name);
    }
}
