<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Client;
use App\Models\Process;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id',
        'name',
        'description',
        'status',
    ];

    public function client () {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function processes () {
        return $this->hasMany(Process::class, 'project_id', 'id');
    }
}
