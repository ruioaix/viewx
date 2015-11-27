<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskC extends Model
{
    protected $table = 'task';
    public $timestamps = false;

    protected function number($type) {
        $num = TaskC::where('type', '=', $type)
            ->count();
        return $num;
    }
}
