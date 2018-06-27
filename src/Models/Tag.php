<?php

namespace WebAppId\Content\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class Tag extends Model
{
    protected $table='tags';

    protected $hidden=['user_id','created_at','updated_at'];

    protected $fillable=['id','name'];

    public function addTag($request){
        try{
            $this->name = $request->name;
            $this->user_id = $request->user_id;
            $this->save();
            return $this;
        }catch(QueryException $e){
            report($e);
            return false;
        }
    }

    public function getTagByName($name){
        return $this->where('name', $name)->first();
    }
}