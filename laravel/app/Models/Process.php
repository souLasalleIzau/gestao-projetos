<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Project;
use App\Models\Stage;

class Process extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id',
        'name',
        'description',
    ];

    public function project () {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function stages () {
        return $this->hasMany(Stage::class, 'process_id', 'id');
    }
}
