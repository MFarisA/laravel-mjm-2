<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class Item extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    
    public function useritems()
    {
        return $this->belongsToMany(Useritem::class, 'item_useritem')->withTimestamps();
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($item) {
            $item->useritems()->detach();
        });
    }
}

