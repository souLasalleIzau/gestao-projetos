<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Stage;

class Tool extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];

    public function tools () {
        return $this->belongsToMany(Stage::class, 'stages_tools', 'tool_id', 'stage_id')
            ->withPivot('type', 'created_at', 'updated_at')
            ->withTimestamps();
    }
}
