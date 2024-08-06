<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_perusahaan',
        // 'users',
        'quantity',
        'deskripsi',
        'jenis_pekerjaan',
        'deadline',
        'picture',
    ];

    public function users(){
        return $this->belongsToMany(User::class, 'project_assigns')->withTimestamps();
    }


    protected function casts(): array
    {
        return [
            'users' => 'array',
        ];
    }
}