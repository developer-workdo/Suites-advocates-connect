<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecentDevelopment extends Model
{
    use HasFactory;

    protected $fillable = ['recent_development_category_id', 'title', 'description', 'created_by'];
}
