<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Adjust extends Model
{
    protected $table = 'zuhe_adjust';
    public $timestamps = false;

    protected function period($beforebefore_tp, $before_tp) {
        $adjustdt = Adjust::where('updated', '>=', $beforebefore_tp)
            ->where('updated', '<=', $before_tp)
            ->select('zid', 'updated')
            ->orderBy('id', 'desc')
            ->get();
        return $adjustdt;
    }
}
