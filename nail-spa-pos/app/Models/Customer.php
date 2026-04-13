<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'notes',
        'last_visit',
        'total_visits',
        'lifetime_value',
    ];

    protected $casts = [
        'last_visit' => 'date',
        'total_visits' => 'integer',
        'lifetime_value' => 'decimal:2',
    ];

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function incrementVisits($amount = 1)
    {
        $this->increment('total_visits', $amount);
        $this->update(['last_visit' => now()]);
    }

    public function addToLifetimeValue($amount)
    {
        $this->increment('lifetime_value', $amount);
    }
}
