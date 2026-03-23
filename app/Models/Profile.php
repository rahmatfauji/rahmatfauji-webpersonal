<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'title',
        'bio',
        'email',
        'phone',
        'location',
        'linkedin_url',
        'github_url',
        'avatar_url',
        'chart_label_1',
        'chart_value_1',
        'chart_label_2',
        'chart_value_2',
        'chart_label_3',
        'chart_value_3',
        'chart_label_4',
        'chart_value_4',
        'expertise_chart',
    ];

    protected $casts = [
        'chart_value_1' => 'integer',
        'chart_value_2' => 'integer',
        'chart_value_3' => 'integer',
        'chart_value_4' => 'integer',
        'expertise_chart' => 'array',
    ];
}
