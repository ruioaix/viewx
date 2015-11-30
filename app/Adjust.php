<?php

namespace App;
use DB;

use Illuminate\Database\Eloquent\Model;

class Adjust extends Model
{
    protected $table = 'zuhe_adjust';
    public $timestamps = false;

    protected function period($beforebefore_tp, $before_tp) {
        #$adjustdt = Adjust::where('updated', '>=', $beforebefore_tp)
        #    ->where('updated', '<=', $before_tp)
        #    ->select('zid', 'updated')
        #    ->orderBy('id', 'desc')
        #    ->get();
        $adjustdt = DB::select("select updated from zuhe_adjust where updated between ? and ?", [$beforebefore_tp, $before_tp]);
        return $adjustdt;
    }

    protected function one($zid) {
        $one = Adjust::where('zid', $zid)
            ->orderBy('id', 'desc')
            ->select('data')
            ->get();
        return $one;
    }


    protected function cheating($zid) {
        $adjustdt = DB::select("select data from zuhe_cheating where zid = ? order by id desc limit 1", [$zid]);
        return $adjustdt;
    }
}
