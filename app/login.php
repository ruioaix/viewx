<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class login extends Model
{
    public $timestamps = false;

    protected function circle_proxy($beforebefore, $before) {
        $data = DB::select("select source,count,time from proxy_source where time between ? and ?", [$beforebefore, $before]);
        return $data;
    }

    protected function circle_account($beforebefore, $before) {
        $data = DB::select("select source,time from account_circle where time between ? and ?", [$beforebefore, $before]);
        return $data;
    }

}
