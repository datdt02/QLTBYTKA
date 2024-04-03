<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExternalQualityAssessment extends Model
{
    use HasFactory;

    protected $table = "external_quality_assessments";

    protected $fillable = [
        "provider",
        "equipment_id",
        "time",
        "note",
        "content",
    ];


    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class,'equipment_id','id');
    }
}
