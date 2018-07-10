<?php

namespace App;

use App\Events\WebsiteUriSaved;
use Illuminate\Database\Eloquent\Model;

class WebsiteUri extends Model
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
        'uri',
    ];

    /*
     * @var array
     */
    protected $casts = [
        'uri' => 'string',
        'status' => 'string',
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'saved' => WebsiteUriSaved::class,
    ];

    public function website()
    {
        return $this->belongsTo('App\Website');
    }
}
