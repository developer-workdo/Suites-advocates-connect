<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticeTool extends Model
{
    use HasFactory;

    protected $fillable = ['practice_tool_category_id', 'title', 'description', 'created_by'];
}
