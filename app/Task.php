<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Task extends Model
{
    protected $table = 'task_record';
    public $timestamps = false;

    protected function period_begin($beforebefore, $before) {
        $data = DB::select("select time,instanceid from success where time between ? and ?", [$beforebefore, $before]);
        return $data;
    }

    protected function period2($beforebefore, $before, $type) {
        $data = DB::select("select time,code from fail where time between ? and ? and type = ?", [$beforebefore, $before, $type]);
        return $data;
    }

    protected function period3($beforebefore, $before, $type) {
        $data = DB::select("select time,code,zid from success where time between ? and ? and type = ?", [$beforebefore, $before, $type]);
        return $data;
    }
}
