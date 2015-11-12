<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Proxy;

class ProxyController extends Controller
{
    public function index()
    {
        $step = 30 * 60;
        $stepNum = 100;
        $now = time();
        $ago = $now - $step * $stepNum;
        $proxies = Proxy::time($ago);
        $data = array(
            'labels' => array(),
            'datasets' => array(
                array(
                    'label'=> "begin",
                    'fillColor' => "rgba(220,220,220,0.2)",
                    'strokeColor' => "rgba(220,220,220,1)",
                    'pointColor' => "rgba(220,220,220,1)",
                    'pointStrokeColor'=> "#fff",
                    'pointHighlightFill'=> "#fff",
                    'pointHighlightStroke'=> "rgba(151,187,205,1)",
                    'data'=> array()
                ),
                array( 
                    'label' => "success",
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
            $data['labels'][$i] = $dt->format("H:i");
            $data['datasets'][0]['data'][$i] = 0;
            $data['datasets'][1]['data'][$i] = 0;
            $data['datasets'][2]['data'][$i] = 0;
        }
        foreach ($proxies as $proxy) {
            $start = $proxy['start_time'];
            $end = $proxy['end_time'];
            if ($start == -1) {
                $i = (int) (($end - $ago) / $step);
                $data['datasets'][2]['data'][$i] += 1;
            }
            elseif ($proxy['message'] == 'success') {
                $i = (int) (($start - $ago) / $step);
                $data['datasets'][1]['data'][$i] += 1;
            }
            else {
                $i = (int) (($start - $ago) / $step);
                $data['datasets'][0]['data'][$i] += 1;
            }
        }
        $proxies = "hello world";

        #$data = array(
        #    'labels' => array("January", "February", "March", "April", "May", "June", "July"),
        #    'datasets' => array(
        #        array( 
        #            'label' => "My First dataset",
        #            'fillColor' => "rgba(220,220,220,0.2)",
        #            'strokeColor' => "rgba(220,220,220,1)",
        #            'pointColor' => "rgba(220,220,220,1)",
        #            'pointStrokeColor' => "#fff",
        #            'pointHighlightFill' => "#fff",
        #            'pointHighlightStroke' => "rgba(220,220,220,1)",
        #            'data' => array(65, 59, 80, 81, 56, 55, 40)
        #        ),
        #        array(
        #            'label'=> "My Second dataset",
        #            'fillColor'=> "rgba(151,187,205,0.2)",
        #            'strokeColor'=> "rgba(151,187,205,1)",
        #            'pointColor'=> "rgba(151,187,205,1)",
        #            'pointStrokeColor'=> "#fff",
        #            'pointHighlightFill'=> "#fff",
        #            'pointHighlightStroke'=> "rgba(151,187,205,1)",
        #            'data'=> array(28, 48, 40, 19, 86, 27, 90)
        #        )
        #    )
        #);
        $data = json_encode($data);
        return view('proxy.main', compact('proxies', 'data'));
    }

}
