<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BhaktiSadan extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'leader_id'];

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }
}
