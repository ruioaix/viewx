<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MainController extends Controller
{
    public function index() {
        return view('home');
    }

    public function onlyhas() {
        return view('rxqs.onlyhas');
    }

    public function onlyin() {
        return view('rxqs.onlyin');
    }

    protected function readf() {
        $nodeids = \File::get(public_path()."/_nodeids", "File Not Found (_nodeids)");
        $nodeids = json_decode($nodeids, true);
        $edges = \File::get(public_path()."/_edges", "File Not Found (_edges)");
        $edges = json_decode($edges, true);
        return compact('nodeids', 'edges');
    }

    protected function all_core() {
        $data = MainController::readf();
        $nodeids = $data['nodeids'];
        $edges = $data['edges'];
        $nodes = array();
        foreach ($nodeids as $id => $node) {
            $nodes[] = "{id: $id, label: '$node', color: '#90C1FE'},";
        }
        $relations = array();
        foreach ($edges as $id => $inhas) {
            foreach($inhas['has'] as $obj) {
                $sign = "$id=>$obj";
                $relations[$sign] = "{from: $id, to: $obj, arrows:'to'},";
            }
            foreach($inhas['in'] as $obj) {
                $sign = "$obj=>$id";
                $relations[$sign] = "{from: $obj, to: $id, arrows:'to'},";
            }
        }
        $url = action('MainController@node', ['']);
        return compact('nodes', 'relations', 'url');
    }

    public function all() {
        $res = MainController::all_core();
        return view('rxqs.all', $res);
    }

    public function node($id) {
        if ($id == -1) {
            $res = MainController::all_core();
            return view('rxqs.js', $res);
        }
        $data = MainController::readf();
        $nodeids = $data['nodeids'];
        $edges = $data['edges'];

        $nodes = array();
        $node = $nodeids[$id];
        $nodes[$id] = "{id: $id, label: '$node', color: '#90C1FE'},";
        $relations = array();
        foreach ($edges[$id]['has'] as $obj) {
            $sign = "$id=>$obj";
            $relations[$sign] = "{from: $id, to: $obj, arrows:'to'},";
            $node = $nodeids[$obj];
            $nodes[$obj] = "{id: $obj, label: '$node', color: '#90C1FE'},";
        }
        foreach($edges[$id]['in'] as $obj) {
            $sign = "$obj=>$id";
            $relations[$sign] = "{from: $obj, to: $id, arrows:'to'},";
            $node = $nodeids[$obj];
            $nodes[$obj] = "{id: $obj, label: '$node', color: '#90C1FE'},";
        }
        $node = 1;
        return view('rxqs.js', compact('nodes', 'relations', 'node'));
    }
}
