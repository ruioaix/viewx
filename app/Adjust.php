<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Adjust extends Model
{
    protected $table = 'zuhe_adjust';
    public $timestamps = false;

    protected function time($ago) {
        $adjustdt = Adjust::where('updated', '>=', $ago)
            ->select('zid', 'updated')
            ->orderBy('id', 'desc')
            ->get();
        return $adjustdt;
    }
}
