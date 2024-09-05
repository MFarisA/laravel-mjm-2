<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function users(){
        return $this->belongsToMany(User::class, 'project_assigns')->withTimestamps();
    }

    public function projects(){
        return $this->belongsToMany(Project::class, 'project_assigns')->withTimestamps();
    }

    protected function casts(): array
    {
        return [
            'users' => 'array',
        ];
    }
}
