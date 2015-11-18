<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'account';
    public $timestamps = false;

    protected function time($ago) {
        $accounts = Account::where('time', '>=', $ago)
            ->orderBy('id', 'desc')
            ->get();
        return $accounts;
    }

    protected function part($code) {
        $part = Account::where('code', '=', $code)
            ->orderBy('id', 'desc')
            ->select('username')
            ->get(1000);
        return $part;
    }

    protected function alist() {
        $alist = Account::orderBy('id', 'desc')
            ->select('username', 'code')
            ->get();
        return $alist;
    }
    
    protected function info($username) {
        $info = Account::where('username', '=', $username)
            ->select('code')
            ->get();
        return $info;
    }

}