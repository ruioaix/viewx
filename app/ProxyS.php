<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProxyS extends Model
{
    protected $table = 'proxy_source';
    public $timestamps = false;

    protected function period($beforebefore, $before) {
        $proxies = ProxyS::where('time', '>=', $beforebefore)
            ->where('time', '<=', $before)
            ->orderBy('id', 'desc')
            ->get();
        return $proxies;
    }

}
