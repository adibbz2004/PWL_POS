<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelModel extends Model
{
    use HasFactory;

    protected $table = 'm_level'; // Sesuaikan dengan nama tabel di database
    protected $primaryKey = 'level_id'; // Sesuaikan dengan primary key tabel
    public $timestamps = true; // Ubah menjadi true jika tabel memiliki created_at dan updated_at

    protected $fillable = ['level_id', 'level_nama']; // Sesuaikan dengan kolom yang ada

    public function users()
    {
        return $this->hasMany(UserModel::class, 'level_id', 'level_id');
    }
}