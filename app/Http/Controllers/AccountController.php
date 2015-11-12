<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Account;

class AccountController extends Controller
{
    public function index() {
        $step = 30 * 60;
        $stepNum = 100;
        $now = time();
        $ago = $now - $step * $stepNum;
        $accounts = Account::time($ago);
        $pm = array(
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
                    'strokeColor' => "rgba(120,220,220,1)",
                    'pointColor' => "rgba(120,220,220,1)",
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
            $pm['labels'][$i] = $dt->format("H:i");
            $pm['datasets'][0]['data'][$i] = 0;
            $pm['datasets'][1]['data'][$i] = 0;
            $pm['datasets'][2]['data'][$i] = 0;
        }
        $msg = array();
        $msgcount = 1;
        foreach ($accounts as $account) {
            $start = $account['start_time'];
            $end = $account['end_time'];
            $message = $account['msg'];
            if (isset($msg[$message])) {
                $msg[$message] += 1;
                $msgcount += 1;
            }
            else {
                $msg[$message] = 1;
                $msgcount += 1;
            }
            if ($start == -1) {
                $i = (int) (($end - $ago - 10) / $step);
                $pm['datasets'][2]['data'][$i] += 1;
            }
            elseif ($message == 'success') {
                $i = (int) (($start - $ago - 10) / $step);
                $pm['datasets'][1]['data'][$i] += 1;
            }
            else {
                $i = (int) (($start - $ago - 10) / $step);
                $pm['datasets'][0]['data'][$i] += 1;
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
        unset($msg['begin']);
        arsort($msg);
        foreach ($msg as $kind => $count) {
            $pe['labels'][] = $kind . '(' . number_format($count/$msgcount * 100, 1) . '%)';
            $pe['datasets'][0]['data'][] = $count;
        }
        $pe = json_encode($pe);
        return view('account.main', compact('pm', 'pe'));
    }

}
