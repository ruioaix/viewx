<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proxy extends Model
{
    protected $table = 'proxy_record';
    public $timestamps = false;

    protected function period($beforebefore, $before) {
        $proxies = Proxy::where('time', '>=', $beforebefore)
            ->where('time', '<=', $before)
            ->orderBy('id', 'desc')
            ->get();
        return $proxies;
    }

}
