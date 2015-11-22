<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'account';
    public $timestamps = false;

    protected function period($beforebefore_tp, $before_tp) {
        $accounts = Account::where('time', '>=', $beforebefore_tp)
            ->where('time', '<=', $before_tp)
            ->orderBy('id', 'desc')
            ->get();
        return $accounts;
    }

    protected function alist() {
        $alist = Account::orderBy('id', 'desc')
            ->select('username', 'code', 'time')
            ->get(50000);
        return $alist;
    }
    
    protected function info($username) {
        $info = Account::where('username', '=', $username)
            ->orderBy('id', 'desc')
            ->select('code', 'time')
            ->get();
        return $info;
    }
}
