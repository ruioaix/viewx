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

    protected function threeline($ago, $step, $stepNum) {
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
        $pm = ProxyController::threeline($ago, $step, $stepNum);
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
        $res = ProxyController::threeline($ago, $step, $stepNum);
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
}
