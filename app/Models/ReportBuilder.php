<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReportBuilder extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'report_builder';

    protected $fillable = [
        'field_name',
        'field_type',
        'option_info',
        'dynamic',
        'master_table_info',
        'parent_id',
    ];

    protected $casts = [
        'option_info' => 'array',
        'master_table_info' => 'array',
    ];

    /**
     * Get all of the children for the ReportBuilder
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }
}
