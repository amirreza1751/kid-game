<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'Sid',
        'Msisdn',
        'TransId',
        'Channel',
        'ShortCode',
        'KeyWord',
        'TransStatus',
        'DateTime',
        'ChargeCode',
        'BasePricePoint',
        'BilledPricePoint',
        'EventType',
        'Status',
        'Validity',
        'NextRenewalDate',
    ];
}
