<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseLawByArea extends Model
{
    use HasFactory;
    protected $fillable = ['case_law_by_area_category_id', 'title', 'description', 'document', 'created_by'];

    public function category()
    {
        return $this->belongsTo(CaseLawByAreaCategory::class, 'case_law_by_area_category_id');
    }
}
