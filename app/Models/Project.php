<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class Project extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    // protected $fillable = [
    //     'nama_perusahaan',
    //     // 'users',
    //     'quantity',
    //     'deskripsi',
    //     'jenis_pekerjaan',
    //     'deadline',
    //     'picture',
    // ];

    public function items(){
        return $this->belongsToMany(Item::class)->withTimestamps();
    }
    

}