<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Task extends Model
{
    protected $table = 'task_record';
    public $timestamps = false;

    protected function period_begin($beforebefore, $before) {
        #$data = Task::where('time', '>=', $beforebefore)
        #    ->where('time', '<=', $before)
        #    ->where('code', '=', -1)
        #    ->orderBy('id', 'desc')
        #    ->select('time', 'type')
        #    ->get();
        $data = DB::select("select time,type from task_record where time between ? and ? and code = -1", [$beforebefore, $before]);
        return $data;
    }

    protected function period2($beforebefore, $before, $type) {
        #$data = Task::where('time', '>=', $beforebefore)
        #    ->where('time', '<=', $before)
        #    ->where('type', '=', $type)
        #    ->orderBy('id', 'desc')
        #    ->get();
        $data = DB::select("select time,code from task_record where time between ? and ? and type = ?", [$beforebefore, $before, $type]);
        #$data = DB::select("select time,code from task_record where id between 0 and 100000 and type = ?", [$type]);
        return $data;
    }

    protected function period3($beforebefore, $before, $type) {
        #$data = Task::where('time', '>=', $beforebefore)
        #    ->where('time', '<=', $before)
        #    ->where('type', '=', $type)
        #    ->orderBy('id', 'desc')
        #    ->get();
        $data = DB::select("select time,code,zid from task_record where time between ? and ? and type = ?", [$beforebefore, $before, $type]);
        return $data;
    }
}
