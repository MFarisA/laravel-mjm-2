<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Useritem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items(){
        return $this->belongsToMany(Item::class, 'item_useritem')->withTimestamps();
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($useritem) {
            $useritem->items()->detach();
        });

        static::updating(function ($useritem) {
            if ($useritem->isDirty('user_id')) {
                $user = $useritem->user;
                $useritem->operator_name = $user ? $user->name : null;
            }
        });
    }


    protected function casts(): array
    {
        return [
            'items' => 'array',
        ];
    }
}
