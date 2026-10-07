<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'locale', 'title', 'summary', 'location', 'client', 'description', 'design_concept', 'architecture_info', 'structural_info', 'engineering_info', 'geotechnical_info', 'construction_info', 'interior_info', 'consultant', 'progress_overview', 'seo'];

    protected $casts = ['seo' => 'array'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
