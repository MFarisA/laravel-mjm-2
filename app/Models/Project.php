<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class Project extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    
    public function items()
    {
        return $this->belongsToMany(Item::class, 'project_item')->withTimestamps();
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($project) {
            $project->items()->detach();
        });
    }
}

