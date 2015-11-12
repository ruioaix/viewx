<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Adjust;

class AdjustController extends Controller
{
    public function index() {
        #return "hello world";
        $step = 30 * 60;
        $stepNum = 100;
        $now = time();
        $ago = $now - $step * $stepNum;
        $adjustdt = Adjust::time($ago);
        $pm = array(
            'labels' => array(),
            'datasets' => array(
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
            ),
        );
        for ($i = 0; $i < $stepNum; $i++ ) {
            $ts = $ago + $step * ($i + 1);
            $dt = new \DateTime("@$ts");
            $dt->setTimeZone(new \DateTimeZone('Europe/Zurich'));
            $pm['labels'][$i] = $dt->format("H:i");
            $pm['datasets'][0]['data'][$i] = 0;
        }
        foreach ($adjustdt as $adj) {
            $updated = $adj['updated'];
            $i = (int) (($updated - $ago - 10) / $step);
            $pm['datasets'][0]['data'][$i] += 1;
        }
        $adj = json_encode($pm);

        return view('adjust.main', compact('adj'));
    }

}
