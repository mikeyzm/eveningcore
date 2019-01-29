<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConvertOption extends Model
{
    protected $guarded = [];

    protected $touches = ['convert'];

    public $timestamps = false;

    public function convert()
    {
        return $this->belongsTo(Convert::class);
    }
}
