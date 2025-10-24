<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    public $guarded = [];

    protected $casts = [
        'due_date' => 'datetime',
    ];


    public function tasks()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function scopeDueDate(Builder $query, ...$date)
    {
        $from = isset($date[1]) ? Carbon::parse($date[0]) : Carbon::parse($date[0])->startOfDay();
        $to = isset($date[1]) ? Carbon::parse($date[1])->add(fn(Carbon $date) => $date->hour == 0 ? $date->endOfDay() : $date) : Carbon::parse($date[0])->endOfDay();
        return $query->whereBetween('due_date', [$from, $to]);
    }

    public function scopeStatus(Builder $query, ...$status)
    {
        return $query->whereIn('status', $status);
    }
}
