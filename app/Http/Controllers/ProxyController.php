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
    public function errortype() {
        return view('proxy.errortype');
    }

    protected function threeline() {
        $data = array(
            'labels' => array(),
            'datasets' => array(
                array(
                    'label'=> "one",
                    'fillColor' => "rgba(220,220,220,0.2)",
                    'strokeColor' => "rgba(220,220,220,1)",
                    'pointColor' => "rgba(220,220,220,1)",
                    'pointStrokeColor'=> "#fff",
                    'pointHighlightFill'=> "#fff",
                    'pointHighlightStroke'=> "rgba(151,187,205,1)",
                    'data'=> array()
                ),
                array( 
                    'label' => "two",
                    'fillColor'=> "rgba(151,187,205,0.2)",
                    'strokeColor'=> "rgba(151,187,205,1)",
                    'pointColor'=> "rgba(151,187,205,1)",
                    'pointStrokeColor' => "#fff",
                    'pointHighlightFill' => "#fff",
                    'pointHighlightStroke' => "rgba(220,220,220,1)",
                    'data' => array()
                ),
                array(
                    'label'=> "end",
                    'fillColor' => "rgba(220,220,220,0.2)",
                    'strokeColor' => "rgba(220,220,220,1)",
                    'pointColor' => "rgba(220,220,220,1)",
                    'pointStrokeColor'=> "#fff",
                    'pointHighlightFill'=> "#fff",
                    'pointHighlightStroke'=> "rgba(151,187,205,1)",
                    'data'=> array()
                ),
            ),
        );
        return $data;
    }

    protected function threeline_init($ago, $step, $stepNum) {
        $data = ProxyController::threeline();
        for ($i = 0; $i < $stepNum; $i++ ) {
            $ts = $ago + $step * ($i + 1);
            $dt = new \DateTime("@$ts");
            $dt->setTimeZone(new \DateTimeZone('Europe/Zurich'));
            $data['labels'][$i] = $dt->format("m-d H:i");
            $data['datasets'][0]['data'][$i] = 0;
            $data['datasets'][1]['data'][$i] = 0;
            $data['datasets'][2]['data'][$i] = 0;
        }
        return $data;
    }

    protected function status($step) {
        $stepNum = 60;
        $now = time();
        $ago = $now - $step * $stepNum;
        $proxies = Proxy::time($ago);
        $pm = ProxyController::threeline_init($ago, $step, $stepNum);
        $codes = array();
        $codescount = 1;
        foreach ($proxies as $proxy) {
            $time = $proxy['time'];
            $code = $proxy['code'];
            if (isset($codes[(string)$code])) {
                $codes[(string)$code] += 1;
                $codescount += 1;
            }
            else {
                $codes[(string)$code] = 1;
                $codescount += 1;
            }
            $i = (int) (($time - $ago - 10) / $step);
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

        $pe = array(
            'labels' => array(),
            'datasets' => array(
                array(
                    'label'=> "error",
                    'fillColor' => "rgba(151,187,205,0.5)",
                    'strokeColor' => "rgba(151,187,205,0.8)",
                    'highlightFill' => "rgba(151,187,205,0.75)",
                    'highlightStroke' => "rgba(151,187,205,1)",
                    'data'=> array()
                ),
            ),
        );
        unset($codes['-1']);
        arsort($codes);
        $paes = Variables::get('paerror');
        $paes = json_decode($paes->value, True, 3);
        
        foreach ($codes as $kind => $count) {
            $pe['labels'][] = $paes[$kind] . '(' . number_format($count/$codescount * 100, 1) . '%)';
            $pe['datasets'][0]['data'][] = $count;
        }
        $pe = json_encode($pe);
        return compact('pm', 'pe', 'step');
    }

    public function index() {
        $res = ProxyController::status(3600); 
        return view('proxy.main', $res);
    }

    public function step($step) {
        $res = ProxyController::status($step); 
        return view('proxy.sjs', $res);
    }

    public function source_status($step) {
        $stepNum = 60;
        $now = time();
        $ago = $now - $step * $stepNum;
        $slist = ProxyS::time($ago);
        $res = ProxyController::threeline_init($ago, $step, $stepNum);
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
            return floor($time/60);
        }
        if ($time <= 3600) {
            return 9 + floor($time/600);
        }
        if ($time <= 24 * 3600) {
            return 14 + floor($time/3600);
        }
        return $count;
    }

    protected function wtime_core($step) {
        $stepNum = 60;
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

        $ipportset = array();
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
                    $source_usage_rate_exact['all'][$source] += 1;
                } 
                elseif ($code == 0) {
                    $source_time[$source][] = $ipportset[$ipport] - $time;
                    $source_usage_rate_exact['success'][$source] += 1;
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
                }
            }
        }

        $x = array(
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
            $res = ProxyController::threeline($ago, $step, $stepNum);
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

        return compact('res_a', 'step', 'stepNum', 'source_usage_rate', 'source_usage_rate_exact');
    }

    public function wtime() {
        $res = ProxyController::wtime_core(3600);
        return view('proxy.workingtime', $res);
    }

    public function wstep($step) {
        $res = ProxyController::wtime_core($step);
        return view('proxy.wjs', $res);
    }

}
