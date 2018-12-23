<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TempTransactionId extends Model
{
    protected $fillable = ['msisdn', 'otp_transaction_id'];
    protected $table = 'temp_transaction_ids';
}
