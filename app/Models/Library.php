<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Library extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_id',
        'legal_document',
        'template',
        'case_law',
        'statute_regulation',
        'practice_guide',
        'form_checklist',
        'article_publication',
        'firm_policy_procedure',
        'research_tool',
        'training_material',
        'created_by',
    ];

    public static function getCasesById($id){

        $cases = Cases::whereIn('id',explode(',',$id))->pluck('title')->toArray();
        return implode(',',$cases);
    }

    public function case()
    {
        return $this->hasOne(Cases::class, 'id', 'case_id');
    }
}
