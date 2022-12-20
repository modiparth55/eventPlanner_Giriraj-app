<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Events extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'events';

    protected $fillable = [
        'event_title',
        'event_start_date',
        'event_end_date',
        'event_description',
        'event_recurrence_type',
    ];

    public function events()
    {
        return $this->hasMany(Events::class, 'event_id', 'id');
    }

    public function event()
    {
        return $this->belongsTo(Events::class, 'event_id');
    }
}
