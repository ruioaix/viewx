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
    protected function status($ago_secord, $period_secord) {
        #$period_secord > 3600s, 1h.
        #$stepNum for now 60, never > 100.
        #so, $step_secord > 36s.
        $stepNum = Variables::getStepNum();
        $step_secord = (int)($period_secord / $stepNum);
        $now = time();
        #_tp means time point.
        $before_tp = $now - $ago_secord; 
        $beforebefore_tp = $before_tp - $period_secord;
        $proxies = Proxy::period($beforebefore_tp, $before_tp);
        $pm = Variables::chartjs_line_three_inited_with_time($beforebefore_tp, $step_secord, $stepNum);
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
        $paes = Variables::get('paerror');
        $paes = json_decode($paes->value, True, 3);
        foreach ($codes as $kind => $count) {
            $pe['labels'][] = $paes[$kind] . '(' . number_format($count/$codescount * 100, 1) . '%)';
            $pe['datasets'][0]['data'][] = $count;
        }
        $pe = json_encode($pe);
        return compact('pm', 'pe', 'step_secord');
    }

    public function monitor() {
        $res = ProxyController::status(0, 3600 * 60); 
        return view('proxy.main', $res);
    }

    public function mstep($ago_period) {
        $args = explode("-", $ago_period);
        $ago_secord = (int)($args[0]);
        $period_secord = (int)($args[1]);
        $res = ProxyController::status($ago_secord, $period_secord); 
        return view('proxy.sjs', $res);
    }

    public function source_status($step) {
        $stepNum = Variables::getStepNum();
        $now = time();
        $ago = $now - $step * $stepNum;
        $slist = ProxyS::time($ago);
        $res = Variables::chartjs_line_three_inited_with_time($ago, $step, $stepNum);
        foreach ($slist as $sone) {
            $source = $sone['source'];
            $count = $sone['count'];
            $time = $sone['time'];

            $i = (int) (($time - $ago - 10) / $step);
            if ($source == 8) {
                $res['datasets'][0]['data'][$i] += $count;
            }
            elseif ($source == 9) {
                $res['datasets'][1]['data'][$i] += $count;
            }
            elseif ($source == 10)  {
                $res['datasets'][2]['data'][$i] += $count;
            }
        }
        $res = json_encode($res);
        return compact('res', 'step');
    }

    public function source() {
        $res = ProxyController::source_status(3600);
        return view('proxy.source', $res);
    }

    public function sstep($step) {
        $res = ProxyController::source_status($step); 
        return view('proxy.ssjs', $res);
    }

    protected function timetox($time, $count) {
        if ($time <= 600) {
            return ceil($time/60);
        }
        if ($time <= 3600) {
            return 9 + ceil($time/600);
        }
        if ($time <= 24 * 3600) {
            return 13 + ceil($time/1800);
        }
        return $count;
    }

    protected function wtime_core($step) {
        $stepNum = Variables::getStepNum();
        $now = time();
        $ago = $now - $step * $stepNum;
        $slist = Proxy::time($ago);

        $source_kind = array(8, 9, 10);
        $source_time = array();
        $source_usage_rate = array(
            'success' => array(),
            'all' => array(),
        );
        $source_usage_rate_exact = array(
            'success' => array(),
            'all' => array(),
        );
        foreach ($source_kind as $sk) {
            $source_usage_rate['success'][$sk] = 0;
            $source_usage_rate['all'][$sk] = 0;
            $source_usage_rate_exact['success'][$sk] = 0;
            $source_usage_rate_exact['all'][$sk] = 0;
            $source_time[$sk] = array();
        }

        $codedist = array(
            300 => array(),
            3600 => array(),
            14400 => array(),
            'MORE' => array(),
        );

        $ipportset = array();
        $codeset = array();
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
                    var_dump($ipport);
                    return redirect('/');
                }
            }
            else {
                if ($code == -1 || $code == 0) {
                    continue;
                }
                else{
                    $ipportset[$ipport] = $time;
                    $codeset[$ipport] = $code;
                }
            }
        }

        $x = array(0,
            60, 120, 180, 240, 300, 360, 420, 480, 540, 600, #0-9
            1200, 1800, 2400, 3000, 3600, #10-14
            5400, 7200, #15, 16
            9000, 10800, 12600, 14400, 16200, 18000, 19800, 21600, 23400, 25200, #17-26
            27000, 28800, 30600, 32400, 34200, 36000, 37800, 39600, 41400, 43200, #27-36
            45000, 46800, 48600, 50400, 52200, 54000, 55800, 57600, 59400, 61200, #37-46
            63000, 64800, 66600, 68400, 70200, 72000, 73800, 75600, 77400, 79200, #47-56
            81000, 82800, 84600, 86400 #57-60
        );


        $res_a = array();
        foreach ($source_time as $source => $times) {
            $res = Variables::chartjs_line_three();
            foreach ($x as $key => $time) {
                $hour = (int)($time/3600);
                $minute = (int)($time%3600/60);
                $secord = (int)($time%60);
                if ($hour == 0) { $hour = ''; }
                else { $hour = (string)$hour . 'H'; }
                if ($minute == 0) { $minute = ''; }
                else { $minute = (string)$minute . 'M';}
                if ($secord == 0) { $secord = ''; }
                else {$secord = (string)$secord . 'S';}
                $time = $hour.$minute.$secord;
                $res['labels'][$key] = $time;
                $res['datasets'][0]['data'][$key] = 0;
            }
            $res['labels'][count($x)] = 'MORE';
            $res['datasets'][0]['data'][count($x)] = 0;
            foreach ($times as $time) {
                $id = ProxyController::timetox($time, count($x));
                $res['datasets'][0]['data'][$id] += 1;
            }
            $res_a[$source] = json_encode($res);
        }

        $code_res_a = array();
        $paes = Variables::get('paerror');
        $paes = json_decode($paes->value, True, 3);
        foreach ($codedist as $time => $codes) {
            $res = Variables::chartjs_line_three();
            arsort($codes);
            foreach ($codes as $code => $count) {
                $res['labels'][] = $paes[$code];
                $res['datasets'][0]['data'][] = $count;
            }
            $code_res_a[$time] = json_encode($res);
        }

        return compact('res_a', 'code_res_a', 'step', 'stepNum', 'source_usage_rate', 'source_usage_rate_exact');
    }

    public function wtime() {
        $res = ProxyController::wtime_core(3600);
        return view('proxy.workingtime', $res);
    }

    public function wstep($step) {
        $res = ProxyController::wtime_core($step);
        return view('proxy.wjs', $res);
    }

    public function errortype() {
        $paes = Variables::get('paerror');
        $paes = json_decode($paes->value, True, 3);
        ksort($paes);
        return view('proxy.errortype', compact('paes'));
    }

}
