<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Circle extends Model
{
    public $timestamps = false;

    protected function circle($beforebefore, $before) {
        $data = DB::select("select type,value,time from circle where time between ? and ?", [$beforebefore, $before]);
        return $data;
    }

}
