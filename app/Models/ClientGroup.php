<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientGroup extends Model
{
    protected $fillable = [
        'name',
        'status',
    ];

    public function hospitals()
    {
        return $this->belongsToMany(
            Hospital::class,
            'client_group_hospital',
            'client_group_id',
            'hospital_id'
        );
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}