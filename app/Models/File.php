<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = ['data_id', 'filename', 'file_path'];

    public function data()
    {
        return $this->belongsTo(Data::class);
    }

    public function getFilePathById($id) {

    }
}

