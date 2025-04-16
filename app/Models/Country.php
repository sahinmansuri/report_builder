<?php

namespace App\Models;

use App\Traits\CreatedByUpdatedBy;
use App\Traits\ModelState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;

    public $table = 'countries';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'capital',
        'citizenship',
        'country_code',
        'currency',
        'currency_code',
        'currency_sub_unit',
        'currency_symbol',
        'currency_decimals',
        'full_name',
        'iso_3166_2',
        'iso_3166_3',
        'name',
        'region_code',
        'sub_region_code',
        'eea',
        'calling_code',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'capital' => 'string',
        'citizenship' => 'string',
        'country_code' => 'string',
        'currency' => 'string',
        'currency_code' => 'string',
        'currency_sub_unit' => 'string',
        'currency_symbol' => 'string',
        'currency_decimals' => 'integer',
        'full_name' => 'string',
        'iso_3166_2' => 'string',
        'iso_3166_3' => 'string',
        'name' => 'string',
        'region_code' => 'string',
        'sub_region_code' => 'string',
        'eea' => 'boolean',
        'calling_code' => 'string',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'country_code' => 'required',
        'iso_3166_2' => 'required',
        'iso_3166_3' => 'required',
        'name' => 'required',
        'region_code' => 'required',
        'sub_region_code' => 'required',
        'eea' => 'required'
    ];
}