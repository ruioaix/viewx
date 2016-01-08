<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProxyS extends Model
{
    protected $table = 'circle';
    public $timestamps = false;

    protected function period($beforebefore, $before) {
        $proxies = ProxyS::where('time', '>=', $beforebefore)
            ->where('time', '<=', $before)
            ->orderBy('id', 'desc')
            ->get();
        return $proxies;
    }

}
