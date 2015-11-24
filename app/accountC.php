<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountC extends Model
{
    protected $table = 'account_circle';
    public $timestamps = false;

    protected function period($beforebefore, $before) {
        $circles = AccountC::where('time', '>=', $beforebefore)
            ->where('time', '<=', $before)
            ->orderBy('id', 'desc')
            ->get();
        return $circles;
    }

}
