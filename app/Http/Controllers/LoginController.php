<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Variables;
use App\login;

class LoginController extends Controller
{
    public function circle_core($from_secord, $to_secord) {
        $stepNum = Variables::getStepNum();

        $period_secord = $from_secord - $to_secord;
        $step_secord = (int)($period_secord / $stepNum);
        $now = time();
        #_tp means time point.
        $beforebefore_tp = $now - $from_secord;
        $before_tp = $now - $to_secord; 

        $list = login::circle_proxy($beforebefore_tp, $before_tp);
        $res = Variables::chartjs_line_four_inited_with_time($beforebefore_tp, $step_secord, $stepNum, 'mimvp', 'pachong', 'hidemyass', 'freeproxylists');
        foreach ($list as $lone) {
            $source = $lone->source;
            $count = $lone->count;
            $time = $lone->time;
            $i = (int) (($time - $beforebefore_tp - 10) / $step_secord);
            if ($source == 7) {
                $res['datasets'][0]['data'][$i] += $count;
            }
            elseif ($source == 10) {
                $res['datasets'][1]['data'][$i] += $count;
            }
            elseif ($source == 9) {
                $res['datasets'][2]['data'][$i] += $count;
            }
            elseif ($source == 8)  {
                $res['datasets'][3]['data'][$i] += $count;
            }
        }
        $one = json_encode($res);

        $list = login::circle_account($beforebefore_tp, $before_tp);
        $res = Variables::chartjs_line_one_inited_with_time($beforebefore_tp, $step_secord, $stepNum);
        foreach ($list as $lone) {
            $time = $lone->time;
            $i = (int) (($time - $beforebefore_tp - 10) / $step_secord);
            $res['datasets'][0]['data'][$i] += 1;
        }
        $two = json_encode($res);

        $url = action('LoginController@ccstep', ['']);
        return compact('one', 'two', 'from_secord', 'to_secord', 'url');
    }

    public function circle() {
        $res = LoginController::circle_core(3600 * 60, 0);
        $res['title'] = "Proxy and Account's Circle";
        return view('two_l_ft', $res);
    }

    public function ccstep($from_to) {
        $args = explode("-", $from_to);
        $from_secord = (int)($args[0]) * 3600;
        $to_secord = (int)($args[1]) * 3600;
        $res = LoginController::circle_core($from_secord, $to_secord); 
        return view('two_l_ft_js', $res);
    }

    protected function health_core($from_secord, $to_secord) {
        $stepNum = Variables::getStepNum();
        $period_secord = $from_secord - $to_secord;
        $step_secord = (int)($period_secord / $stepNum);
        $now = time();
        $beforebefore_tp = $now - $from_secord;
        $before_tp = $now - $to_secord; 

        $living = array();
        $list = login::living();
        foreach ($list as $lone) {
            $ipport = $lone->ipv4_port;
            $user = $lone->username;
            $time = $lone->time;
            $living[$ipport] = array(
                'username' => $user,
                'time' => Variables::secordtoHMS($now - $time),
            );
        }

        $pa = Variables::chartjs_line_three_inited_with_time($beforebefore_tp, $step_secord, $stepNum, 'proxy', 'account', 'success');
        $list = login::record($beforebefore_tp, $before_tp);

        $ipportset = array();
        $codeset = array();
        $duration = array();
        $codedist = array( 300 => array(), 3600 => array(), 14400 => array(), 'MORE' => array(), 'all' => array());
        $totaltry = 0.00001;
        $wintry = 0.0;
        $attry = 0.00001;
        foreach ($list as $lone) {
            $ipport = $lone->ipv4_port;
            $user = $lone->username;
            $code = $lone->code;
            $time = $lone->time;

            $i = (int) (($time - $beforebefore_tp) / $step_secord);
            if ($i == $stepNum) { $i -= 1; }

            if (isset($ipportset[$ipport])) {
                if ($code == 0) {
                    $pa['datasets'][2]['data'][$i] += 1;
                    $wintry += 1;
                    $keep =  $ipportset[$ipport] - $time;
                    $duration[] = $keep;
                    if ($keep <= 300) $keep = 300;
                    elseif ($keep <= 3600) $keep = 3600;
                    elseif ($keep <= 14400) $keep = 14400;
                    else $keep = 'MORE';
                    if (isset($codedist[$keep][$codeset[$ipport]])) {
                        $codedist[$keep][$codeset[$ipport]] += 1;
                    }
                    else {
                        $codedist[$keep][$codeset[$ipport]] = 1;
                    }
                    unset($ipportset[$ipport]);
                    unset($codeset[$ipport]);
                }
                else {
                    return redirect('/');
                }
            }
            else {
                if ($code != 0) {
                    $totaltry += 1;
                    $pa['datasets'][0]['data'][$i] += 1;
                    if ($user != 'rvx') {
                        $pa['datasets'][1]['data'][$i] += 1;
                        $attry += 1;
                    }
                    $ipportset[$ipport] = $time;
                    $codeset[$ipport] = $code;

                    if (isset($codedist['all'][$code])) {
                        $codedist['all'][$code] += 1;
                    }
                    else {
                        $codedist['all'][$code] = 1;
                    }
                }
            }
        }
        $awintry = $wintry / $attry;
        $awintry = number_format($awintry * 100, 2);
        $wintry /= $totaltry;
        $wintry = number_format($wintry * 100, 2);
        $pa = json_encode($pa);

        $res = Variables::chartjs_line_one_inited_with_timedist('duration');
        $totalt = 0.0;
        foreach ($duration as $time) {
            $id = Variables::timedist_getindex($time);
            $res['datasets'][0]['data'][$id] += 1;
            $totalt += $time;
        }
        $avet = 0.0;
        if (count($duration)) {
            $avet = Variables::secordtoHMS($totalt / count($duration));
        }
        $duration = json_encode($res);

        $codedists = array();
        $paes = Variables::paerror();
        foreach ($codedist as $time => $codes) {
            $rest = Variables::chartjs_line(1, array('one'));
            arsort($codes);
            foreach ($codes as $code => $count) {
                $rest['labels'][] = $paes[$code];
                $rest['datasets'][0]['data'][] = $count;
            }
            $codedists[$time] = json_encode($rest);
        }

        $url = action('LoginController@hstep', ['']);
        return compact('living', 'pa', 'duration', 'codedists', 'avet', 'wintry', 'awintry', 'from_secord', 'to_secord', 'url');
    }

    public function health() {
        $res = LoginController::health_core(60 * 3600, 0); 
        $res['title'] = "Proxy Monitor";
        return view('login.health', $res);
    }

    public function hstep($from_to) {
        $args = explode("-", $from_to);
        $from_secord = (int)($args[0]) * 3600;
        $to_secord = (int)($args[1]) * 3600;
        $res = LoginController::health_core($from_secord, $to_secord); 
        return view('login.js', $res);
    }
 
}
