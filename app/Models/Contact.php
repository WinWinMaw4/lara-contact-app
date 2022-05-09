<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory;
    use SoftDeletes;

//    protected $table = "contacts";

//query Scope -> local scope
//    public function scopeSearch($query){
//        if(isset(request()->search)){
//            $search = request()->search;
//            return $query->where('name',"LIKE","%$search%")->orWhere('phone',"LIKE","%$search%");
//        }
//    }

}
