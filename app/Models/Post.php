<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public $timestamps  = false;
    protected $guarded =[];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
