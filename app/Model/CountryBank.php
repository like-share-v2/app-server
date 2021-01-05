<?php

declare (strict_types=1);

namespace App\Model;

/**
 * 国家银行卡模型
 *
 * @property int            $id
 * @property int            $country_id
 * @property string         $bank_name
 * @property string         $bank_account
 * @property string         $address
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string         $bank_code
 */
class CountryBank extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'country_bank';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'         => 'integer',
        'country_id' => 'integer',
        'created_at' => 'date:Y-m-d H:i:s',
        'updated_at' => 'date:Y-m-d H:i:s'
    ];
}