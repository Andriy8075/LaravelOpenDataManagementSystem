<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'file_path', 'created_by'];

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function usersWhoSaved()
    {
        return $this->belongsToMany(User::class, 'saved_data', 'data_id', 'user_id');
    }


}

