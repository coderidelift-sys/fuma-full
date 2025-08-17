<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'city',
        'country',
        'capacity',
        'surface_type',
        'description',
        'address',
        'phone',
        'email',
        'website',
        'facilities',
        'status'
    ];

    protected $casts = [
        'facilities' => 'array',
        'capacity' => 'integer',
    ];

    public function matches()
    {
        return $this->hasMany(MatchModel::class, 'venue_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByCity($query, $city)
    {
        return $query->where('city', $city);
    }

    public function scopeByCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    public function scopeByCapacity($query, $minCapacity)
    {
        return $query->where('capacity', '>=', $minCapacity);
    }

    public function getFullAddressAttribute()
    {
        $parts = array_filter([$this->address, $this->city, $this->country]);
        return implode(', ', $parts);
    }

    public function getCapacityFormattedAttribute()
    {
        if ($this->capacity >= 1000000) {
            return number_format($this->capacity / 1000000, 1) . 'M';
        } elseif ($this->capacity >= 1000) {
            return number_format($this->capacity / 1000, 1) . 'K';
        }
        return number_format($this->capacity);
    }
}
