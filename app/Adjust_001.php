<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Adjust_001 extends Model
{
    protected $table = 'zuhe_adjust_001';
    public $timestamps = false;

    protected function period($beforebefore_tp, $before_tp) {
        $adjustdt = Adjust_001::where('updated', '>=', $beforebefore_tp)
            ->where('updated', '<=', $before_tp)
            ->select('zid', 'updated')
            ->orderBy('id', 'desc')
            ->get();
        return $adjustdt;
    }

    protected function one($zid) {
        $one = Adjust_001::where('zid', $zid)
            ->orderBy('id', 'desc')
            ->get();
        return $one;
    }
}
