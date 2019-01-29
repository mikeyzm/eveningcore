<?php

namespace App;

use App\Enums\ConvertStatus;
use App\Events\ConvertStatusUpdated;
use Illuminate\Database\Eloquent\Model;

class Convert extends Model
{
    protected $guarded = [];

    protected $dates = ['expired_at'];

    protected $with = ['options'];

    protected $appends = ['url', 'status_desc'];

    protected static function boot()
    {
        parent::boot();

        static::saved(function (Convert $convert) {
            if ($convert->isDirty('status')) {
                event(new ConvertStatusUpdated($convert));
            }
        });
    }

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

    function getStatusDescAttribute()
    {
        return ConvertStatus::getDescription($this->status);
    }
}
