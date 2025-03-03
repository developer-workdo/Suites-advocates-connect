<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseLawByAreaCategory extends Model
{
    use HasFactory;
    protected $fillable = ['name','created_by'];

    public function caseLawByAreas()
    {
        return $this->hasMany(CaseLawByArea::class, 'case_law_by_area_category_id');
    }
}
