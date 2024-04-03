<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClinicEnvironmentInspection extends Model
{
    use HasFactory;

    protected $table = "clinic_environment_inspections";

    protected $fillable = [
        "provider",
        "equipment_id",
        "time",
        "note",
        "content",
    ];

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class, 'equipment_id', 'id');
    }
}
