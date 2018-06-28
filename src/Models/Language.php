<?php

namespace WebAppId\Content\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class Language extends Model
{
    protected $table = 'languages';
    protected $fillable = ['id', 'code', 'name'];
    protected $hidden = ['user_id', 'created_at', 'updated_at'];

    public function addLanguage($request)
    {
        try{
            $result           = new self();
            $result->code     = $request->code;
            $result->name     = $request->name;
            $result->image_id = $request->image_id;
            $result->user_id  = $request->user_id;
            $result->save();

            return $result;
        }catch(QueryException $e){
            report($e);
            return false;
        }
    }

    public function getLanguage()
    {
        return $this->get();
    }

    public function getLanguageByName($name){
        return $this->where('name',$name)->first();
    }
}
