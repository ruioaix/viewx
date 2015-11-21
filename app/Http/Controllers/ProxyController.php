<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Proxy;
use App\ProxyS;
use App\Variables;

class ProxyController extends Controller
{
    protected function monitor_core($from_secord, $to_secord) {
        #$period_secord > 3600s, 1h.
        #$stepNum for now 60, never > 100.
        #so, $step_secord > 36s.
        $stepNum = Variables::getStepNum();
        $period_secord = $from_secord - $to_secord;
        $step_secord = (int)($period_secord / $stepNum);
        $now = time();
        #_tp means time point.
        $beforebefore_tp = $now - $from_secord;
        $before_tp = $now - $to_secord; 
        $proxies = Proxy::period($beforebefore_tp, $before_tp);
        $pm = Variables::chartjs_line_three_inited_with_time($beforebefore_tp, $step_secord, $stepNum, 'begin', 'success', 'end');
        $codes = array();
        $codescount = 1;
        foreach ($proxies as $proxy) {
            $proxy_tp = $proxy['time'];
            $code = $proxy['code'];
            if (isset($codes[(string)$code])) {
                $codes[(string)$code] += 1;
                $codescount += 1;
            }
            else {
                $codes[(string)$code] = 1;
                $codescount += 1;
            }
            $i = (int) (($proxy_tp - $beforebefore_tp) / $step_secord);
            if ($i == $stepNum) { $i -= 1; }
            if ($code == -1) {
                $pm['datasets'][0]['data'][$i] += 1;
            }
            elseif ($code == 0) {
                $pm['datasets'][1]['data'][$i] += 1;
            }
            else {
                $pm['datasets'][2]['data'][$i] += 1;
            }
        }
        $pm = json_encode($pm);

        $pe = Variables::chartjs_bar_one();
        unset($codes['-1']);
        arsort($codes);
        $paes = Variables::paerror();
        foreach ($codes as $kind => $count) {
            $pe['labels'][] = $paes[$kind] . '(' . number_format($count/$codescount * 100, 1) . '%)';
            $pe['datasets'][0]['data'][] = $count;
        }
        $pe = json_encode($pe);
        $url = action('ProxyController@mstep', ['']);
        return compact('pm', 'pe', 'from_secord', 'to_secord', 'url');
    }

    public function monitor() {
        $res = ProxyController::monitor_core(60 * 3600, 0); 
        return view('proxy.monitor', $res);
    }

    public function mstep($from_to) {
        $args = explode("-", $from_to);
        $from_secord = (int)($args[0]) * 3600;
        $to_secord = (int)($args[1]) * 3600;
        $res = ProxyController::monitor_core($from_secord, $to_secord); 
        return view('proxy.mjs', $res);
    }

    public function circle_core($from_secord, $to_secord) {
        $stepNum = Variables::getStepNum();

        $period_secord = $from_secord - $to_secord;
        $step_secord = (int)($period_secord / $stepNum);
        $now = time();
        #_tp means time point.
        $beforebefore_tp = $now - $from_secord;
        $before_tp = $now - $to_secord; 

        $slist = ProxyS::period($beforebefore_tp, $before_tp);
        $res = Variables::chartjs_line_three_inited_with_time($beforebefore_tp, $step_secord, $stepNum, 'pachong', 'hidemyass', 'freeproxylists');
        foreach ($slist as $sone) {
            $source = $sone['source'];
            $count = $sone['count'];
            $time = $sone['time'];

            $i = (int) (($time - $beforebefore_tp - 10) / $step_secord);
            if ($source == 10) {
                $res['datasets'][0]['data'][$i] += $count;
            }
            elseif ($source == 9) {
                $res['datasets'][1]['data'][$i] += $count;
            }
            elseif ($source == 8)  {
                $res['datasets'][2]['data'][$i] += $count;
            }
        }
        $res = json_encode($res);
        $url = action('ProxyController@cstep', ['']);
        return compact('res', 'from_secord', 'to_secord', 'url');
    }

    public function circle() {
        $res = ProxyController::circle_core(3600 * 60, 0);
        return view('proxy.circle', $res);
    }

    public function cstep($from_to) {
        $args = explode("-", $from_to);
        $from_secord = (int)($args[0]) * 3600;
        $to_secord = (int)($args[1]) * 3600;
        $res = ProxyController::circle_core($from_secord, $to_secord); 
        return view('proxy.cjs', $res);
    }

    protected function health_core($from_secord, $to_secord) {
        $stepNum = Variables::getStepNum();
        $period_secord = $from_secord - $to_secord;
        $step_secord = (int)($period_secord / $stepNum);
        $now = time();
        #_tp means time point.
        $beforebefore_tp = $now - $from_secord;
        $before_tp = $now - $to_secord; 

        $source_kind = array( 8 => 'freeproxylists', 9 => 'hidemyass', 10 => 'pachong');
        $source_time = array();
        $source_usage_rate = array( 'success' => array(), 'all' => array(),);
        $source_usage_rate_exact = array( 'success' => array(), 'all' => array(),);
        foreach ($source_kind as $sk => $st) {
            $source_usage_rate['success'][$sk] = 0;
            $source_usage_rate['all'][$sk] = 0;
            $source_usage_rate_exact['success'][$sk] = 0;
            $source_usage_rate_exact['all'][$sk] = 0;
            $source_time[$sk] = array();
            $aliving[$st] = array();
        }
        $codedist = array( 300 => array(), 3600 => array(), 14400 => array(), 'MORE' => array(),);

        $ipportset = array();
        $codeset = array();
        $slist = Proxy::period($beforebefore_tp, $before_tp);

        foreach ($slist as $proxy) {
            $ipport = $proxy['ipv4_port'];
            $time = $proxy['time'];
            $code = $proxy['code'];
            $source = $proxy['source'];
            if ($code == -1) {
                $source_usage_rate['all'][$source] += 1;
            }
            elseif ($code == 0) {
                $source_usage_rate['success'][$source] += 1;
            }

            if (isset($ipportset[$ipport])) {
                if ($code == -1) {
                    unset($ipportset[$ipport]);
                    unset($codeset[$ipport]);
                    $source_usage_rate_exact['all'][$source] += 1;
                } 
                elseif ($code == 0) {
                    $keep =  $ipportset[$ipport] - $time;
                    $source_time[$source][] = $keep;
                    $source_usage_rate_exact['success'][$source] += 1;

                    if ($keep <= 300) {
                        $keep = 300;
                    }
                    elseif ($keep <= 3600) {
                        $keep = 3600;
                    }
                    elseif ($keep <= 14400) {
                        $keep = 14400;
                    }
                    else {
                        $keep = 'MORE';
                    }
                    if (isset($codedist[$keep][$codeset[$ipport]])) {
                        $codedist[$keep][$codeset[$ipport]] += 1;
                    }
                    else {
                        $codedist[$keep][$codeset[$ipport]] = 1;
                    }
                }
                else {
                    return redirect('/');
                }
            }
            else {
                if ($code == -1) {
                    continue;
                }
                elseif ($code == 0) {
                    $aliving[$source_kind[$source]][$ipport] = Variables::secordtoHMS($now - $time);
                }
                else{
                    $ipportset[$ipport] = $time;
                    $codeset[$ipport] = $code;
                }
            }
        }

        

        $usage = array();
        foreach ($source_kind as $sourceid => $sourcestring) {
            $sus = $source_usage_rate['success'][$sourceid];
            $all = $source_usage_rate['all'][$sourceid];
            $sus_e = $source_usage_rate_exact['success'][$sourceid];
            $all_e = $source_usage_rate_exact['all'][$sourceid];
            if ($all != 0) {
                $value = number_format(100 * $sus/$all, 2);
            }
            else {
                $value = 0;
            }
            $usage[$sourcestring] = "$sus".'/'."$all".' ('."$sus_e".'/'."$all_e".') '."$value".'%.';
            $cnt = count($aliving[$sourcestring]); 
            $usage[$sourcestring] .= " Aliving connections: $cnt.";
        }

        $res_a = array();
        foreach ($source_time as $source => $times) {
            $res = Variables::chartjs_line_one_inited_with_timedist($source_kind[$source]);
            foreach ($times as $time) {
                $id = Variables::timedist_getindex($time);
                $res['datasets'][0]['data'][$id] += 1;
            }
            $res_a[$source_kind[$source]] = json_encode($res);
        }

        $code_res_a = array();
        $paes = Variables::paerror();
        foreach ($codedist as $time => $codes) {
            $rest = Variables::chartjs_line_one('one');
            arsort($codes);
            foreach ($codes as $code => $count) {
                $rest['labels'][] = $paes[$code];
                $rest['datasets'][0]['data'][] = $count;
            }
            $code_res_a[$time] = json_encode($rest);
        }

        $url = action('ProxyController@hstep', ['']);
        return compact('res_a', 'code_res_a', 'from_secord', 'to_secord', 'usage', 'aliving', 'url');
    }

    public function health() {
        $res = ProxyController::health_core(3600 * 60, 0);
        return view('proxy.health', $res);
    }

    public function hstep($from_to) {
        $args = explode("-", $from_to);
        $from_secord = (int)($args[0]) * 3600;
        $to_secord = (int)($args[1]) * 3600;
        $res = ProxyController::health_core($from_secord, $to_secord);
        return view('proxy.hjs', $res);
    }

    public function errortype() {
        $paes = Variables::paerror();
        ksort($paes);
        return view('proxy.errortype', compact('paes'));
    }

}
