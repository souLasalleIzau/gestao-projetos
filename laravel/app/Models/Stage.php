<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Process;
use App\Models\Area;
use App\Models\Tool;

class Stage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'process_id',
        'area_id',
        'name',
        'description',
    ];

    public function process () {
        return $this->belongsTo(Process::class, 'process_id', 'id');
    }

    public function area () {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }

    public function tools () {
        return $this->belongsToMany(Tool::class, 'stages_tools', 'stage_id', 'tool_id')
            ->withPivot('type', 'created_at', 'updated_at')
            ->withTimestamps();
    }
}
