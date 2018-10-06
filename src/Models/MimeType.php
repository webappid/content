<?php

namespace WebAppId\Content\Models;

use WebAppId\Content\Models\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class MimeType extends Model
{
    protected $table = 'mime_types';
    protected $fillable = ['id', 'name'];
    protected $hidden = ['user_id', 'created_at', 'updated_at'];

    public function addMimeType($request)
    {
        try{
            $result          = new self();
            $result->name    = $request->name;
            $result->user_id = $request->user_id;
            $result->save();

            return $result;
        }catch(QueryException $e){
            report($e);
            return false;
        }
    }

    public function getOne($id)
    {
        return $this->findOrFail($id);
    }

    /**
     * Get Mime Type By Name
     *
     * @param String $name
     * @return Object $mimeType
     */
    public function getMimeByName($name)
    {
        return $this->where('name', $name)->get();
    }

    public function file()
    {
        return $this->hasOne(File::class);
    }
}
