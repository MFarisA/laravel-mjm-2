<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProjectAssign extends Pivot {

    use HasFactory;

    protected $table = 'project_assigns';

    protected $guarded = ['id'];

    public function project(){
        return $this->belongsTo(Project::class);
    }

    public function item(){
        return $this->belongsTo(Item::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

}
