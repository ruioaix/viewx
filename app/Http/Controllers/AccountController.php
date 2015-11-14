<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Account;
use App\Variables;

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
        $codes = array();
        $codescount = 1;
        foreach ($accounts as $account) {
            $time = $account['time'];
            $code = $account['code'];
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
        return view('account.main', compact('pm', 'pe'));
    }

    protected function goodorbad_core($code) {
        $accounts = Account::part($code);
        $uns = array();
        foreach ($accounts as $account) {
            $username = $account['username'];
            if (isset($uns[$username])) {
                $uns[$username] += 1;
            }
            else {
                $uns[$username] = 1;
            }
        }
        $res = array(
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
        arsort($uns);
        $uns = array_slice($uns, 0, 50);
        foreach ($uns as $name => $count) {
            #return $count;
            #return $name;
            $res['labels'][] = $name;
            $res['datasets'][0]['data'][] = $count;
        }
        $res = json_encode($res);
        return $res;
    }

    public function goodorbad() {
        $sus = AccountController::goodorbad_core(0);
        $cookies = AccountController::goodorbad_core(9302);
        $lfalse = AccountController::goodorbad_core(9400);
        return view('account.part', compact('sus', 'cookies', 'lfalse'));
    }

    public function alist() {
        $accounts = Account::alist();
        $uns = array();
        foreach ($accounts as $account) {
            $username = $account['username'];
            $code = $account['code'];
            if (isset($uns[$username][$code])) {
                $uns[$username][$code] += 1;
            }
            else {
                $uns[$username][$code] = 1;
            }
        }
        $res = array();
        foreach ($uns as $username => $codes) {
            $tmp = array();
            $tmp[0] = $username;
            $tmp[1] = 0;
            $tmp[2] = 0;
            $tmp[3] = 0;
            $tmp[4] = 0;
            foreach ($codes as $code => $count) {
                if ($code == 0) {
                    $tmp[1] = $count;
                }
                elseif ($code == 9302) {
                    $tmp[2] = $count;
                }
                elseif ($code == 9400) {
                    $tmp[3] = $count;
                }
                elseif ($code != -1) {
                    $tmp[4] += $count;
                }
            }
            $res[] = $tmp;
        }
        return view('account.list', compact('res'));
    }

    public function info($un) {
        $un = str_replace('_', '.', $un);
        $uns = Account::info($un);
        $codes = array();
        foreach ($uns as $uc) {
            $code = $uc['code'];
            if (isset($codes[$code])) {
                $codes[$code] += 1;
            }
            else {
                $codes[$code] = 1;
            }
        }
        unset($codes[-1]);
        $res = array(
            'labels'=> array(),
            'datasets'=> array(
                array(
                    'label'=> "Error",
                    'fillColor' => "rgba(151,187,205,0.5)",
                    'strokeColor' => "rgba(151,187,205,0.8)",
                    'pointColor'=> "rgba(151,187,205,1)",
                    'pointStrokeColor' => "#fff",
                    'pointHighlightFill' => "#fff",
                    'pointHighlightStroke'=> "rgba(151,187,205,1)",
                    'data'=> array(),
                )
            ),
        );
        $paes = Variables::get('paerror');
        $paes = json_decode($paes->value, True, 3);
        foreach ($codes as $code => $count) {
            $res['labels'][] = $paes[$code];
            $res['datasets'][0]['data'][] = $count;
        }
        $res = json_encode($res);
        return view('account.info', compact('res', 'un'));
    }
}
