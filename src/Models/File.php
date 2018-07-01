<?php

namespace WebAppId\Content\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class File extends Model
{
    protected $table='files';

    protected $hidden=['created_at','updated_at'];

    protected $fillable=['id','name','description','alt','mime_type_id', 'owner_id','user_id'];

    public function addFile($request){
        try{
            $result               = new self();
            $result->name         = $request->name;
            $result->description  = $request->description;
            $result->alt          = $request->alt;
            $result->path         = $request->path;
            $result->mime_type_id = $request->mime_type_id;
            $result->owner_id     = $request->owner_id;
            $result->user_id      = $request->user_id;
            $result->save();
            
            return $result;
        }catch(QueryException $e){
            report($e);
            return false;
        }
    }

    public function getOne($id){
        return $this->findOrFail($id);
    }

    public function getFileByName($name){
        return $this->where('name',$name)->first();
    }

    public function mime(){
        return $this->belongsTo('Loketics\Models\MimeType');
    }

    public function content(){
        return $this->belongsTo('Loketics\Models\Content');
    }

    public function getFileCount(){
        return $this->count();
    }
}
