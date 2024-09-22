<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Item extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function projects(){
        return $this->belongsToMany(Project::class, 'project_item')->withTimestamps();
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($item) {
            $item->projects()->detach();
        });

        static::updating(function ($item) {
            if ($item->isDirty('user_id')) { 
                $user = $item->user; 
                $item->operator_name = $user ? $user->name : null; 
            }
        });
    }


    protected function casts(): array
    {
        return [
            'projects' => 'array',
        ];
    }
}
