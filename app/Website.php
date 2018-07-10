<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    const STATUS_NEW = 'new';
    const STATUS_RUNNING = 'running';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'uri', 'status',
    ];

    /*
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'uri' => 'string',
        'status' => 'string',
    ];

    public function uris()
    {
        return $this->hasMany('App\WebsiteUri');
    }
}
