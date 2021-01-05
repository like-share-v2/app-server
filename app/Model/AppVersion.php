<?php

declare (strict_types=1);

namespace App\Model;

/**
 * @property int            $id
 * @property string         $name
 * @property string         $version
 * @property int            $version_number
 * @property string         $description
 * @property boolean        $is_mandatory
 * @property string         $download_url
 * @property int            $update_mode
 * @property int            $update_time
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class AppVersion extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'app_version';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'             => 'integer',
        'version_number' => 'integer',
        'is_mandatory'   => 'boolean',
        'update_mode'    => 'integer',
        'update_time'    => 'integer',
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime'
    ];
}