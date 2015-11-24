<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'task_record';
    public $timestamps = false;

    protected function period_begin($beforebefore, $before) {
        $data = Task::where('time', '>=', $beforebefore)
            ->where('time', '<=', $before)
            ->where('code', '=', -1)
            ->orderBy('id', 'desc')
            ->get();
        return $data;
    }
}
