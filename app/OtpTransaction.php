<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OtpTransaction extends Model
{
    protected $fillable = ['user_id', 'otp_transaction_id'];
    protected $table = 'otp_transaction_ids';
}
