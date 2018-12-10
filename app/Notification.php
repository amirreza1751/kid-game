<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'sid',
        'msisdn',
        'trans-id',
        'trans-status',
        'datetime',
        'channel',
        'shortcode',
        'keyword',
        'charge-code',
        'billed-price-point',
        'event-type',
        'validity',
        'next_renewal_date',
        'status',
    ];
}
