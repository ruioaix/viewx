<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'account';
    public $timestamps = false;

    protected function time($ago) {
        $accounts = Account::where('start_time', '>=', $ago)
            ->orWhere('end_time', '>=', $ago)
            ->orderBy('id', 'desc')
            ->get();
        return $accounts;
    }
}
