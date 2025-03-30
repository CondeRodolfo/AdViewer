<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'external_id',
        'name',
        'description',
        'additional_information',
        'app_id',
        'click_url',
        'creatives_url',
        'kpi',
        'leadflow',
        'payout',
        'payout_currency',
        'preview_url',
        'restrictions',
        'targeting',
        'app_categories',
        'icons',
        'thumbs',
        'event_types',
        'landing_page_html_templates',
        'sub_sources',
        'raw_data',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'raw_data' => 'array',
        'targeting' => 'array',
        'app_categories' => 'array',
        'icons' => 'array',
        'thumbs' => 'array',
        'event_types' => 'array',
        'landing_page_html_templates' => 'array',
        'sub_sources' => 'array',
        'payout' => 'float',
    ];
} 