<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Variables extends Model
{
    protected $table = 'variables';
    public $timestamps = false;

    protected function get($name) {
        $var = Variables::where('name', '=', $name)
            ->value('value');
        return $var;
    }

    protected function one($name) {
        $var = Variables::where('name', '=', $name)
            ->first();
        return $var;
    }

    protected function set($name, $value) {
        Variables::where('name', $name)
            ->update(['value' => $value, 'time' => time()]);
    }

    protected function paerror() {
        $error = array(
            0 => "Success",
            101 => "CE-ProxyError", 
            102 => "CE-ConnectTimeoutError",
            103 => "CE-ConnectionAborted",
            104 => "CE-ReadTimeOut",
            190 => "CE-unknown",

            201 => "TO-ReadTimeOut",
            290 => "TO-unknown",

            301 => "SK-TimedOut",
            302 => "SK-ConnectionReset",
            390 => "SK-unknown",

            901 => "OE-IncompleteRead",
            990 => "OE-unknown",

            1307=> "LG-307",
            1400=> "LG-400",
            1403=> "LG-403",
            1404=> "LG-404",
            1405=> "LG-405",
            1407=> "LG-407", 
            1411=> "LG-411",
            1434=> "LG-434",
            1500=> "LG-500", 
            1501=> "LG-501",
            1502=> "LG-502", 
            1503=> "LG-503",
            1504=> "LG-504",
            1990=> "LG-unknown",

            2200=> "INFO-200",
            2404=> "INFO-404",
            2990=> "INFO-unknown",

            3200=> "GAIN-200",
            3400=> "GAIN-400",
            3990=> "GAIN-unknown",

            4200=> "ADJ-200",
            4201=> "ADJ-200-merge01",
            4202=> "ADJ-200-merge02",
            4203=> "ADJ-200-merge03",
            4210=> "ADJ-200-endid01",
            4212=> "ADJ-200-endid02",
            4400=> "ADJ-400",
            4401=> "ADJ-400-pageLimit",
            4402=> "ADJ-400-loginAgain",
            4990=> "ADJ-unknown",
            4999=> "ADJ-continue",

            5200=> "RANK-200",
            5400=> "RANK-400",
            5402=> "RANK-400-loginAgain",
            5990=> "RANK-unknown",
            5999=> "RANK-continue",

            9200=> "XQ-Verification",
            9302=> "LG-CookiesWrong",
            9400=> "LG-AccountError",
            9404=> "XQ-NonExist",
        );
        return $error;
    }

    protected function getcolor($id) {
        $colors = array(
            0 => "rgba(171, 201, 221, 0.8)",
            1 => "rgba(171, 201, 221, 1)",

            2 => "rgba(174, 221, 204, 0.8)",
            3 => "rgba(174, 221, 204, 1)",

            4 => "rgba(238, 236, 193, 0.8)",
            5 => "rgba(238, 236, 193, 1)",

            6 => "rgba(232, 209, 231, 0.8)",
            7 => "rgba(232, 209, 231, 1)",

            8 => "rgba(79, 195, 247, 0.8)",
            9 => "rgba(79, 195, 247, 1)",

            10 => "rgba(3, 169, 244, 0.8)",
            11 => "rgba(3, 169, 244, 1)",

            12 => "rgba(228, 82, 27, 0.8)",
            13 => "rgba(228, 82, 27, 1)",

            90 => "rgba(171, 201, 221, 0.5)",
        );
        return $colors[$id];
    }

    protected function chartjs_line($num, $msgs) {
        $data = array(
            'labels' => array(),
            'datasets' => array(
            )
        );
        for ($i = 0; $i < $num; $i++) {
            $j = 2 * $i;
            $one = array(
                'label'=> $msgs[$i],
                'fillColor' => "rgba(0,0,0,0)",
                'strokeColor' => Variables::getcolor($j),
                'pointColor' => Variables::getcolor($j + 1),
                'pointStrokeColor'=> "#fff",
                'pointHighlightFill'=> "#fff",
                'pointHighlightStroke'=> Variables::getcolor($j),
                'data'=> array()
            );
            $data['datasets'][] = $one;
        }
        return $data;
    }

    protected function chartjs_line_inited_with_time($num, $msg, $beforebefore_tp, $step_secord, $stepNum) {
        $data = Variables::chartjs_line($num, $msg);
        $now = time();
        for ($i = 0; $i < $stepNum; $i++ ) {
            $ts = $beforebefore_tp + $step_secord * ($i + 1);
            $dt = new \DateTime("@$ts");
            $dt->setTimeZone(new \DateTimeZone('Europe/Zurich'));
            $hours = (int)(($now - $ts)/3600);
            $data['labels'][$i] = $hours . 'h ' . $dt->format("m-d H:i");
            for ($j = 0; $j < $num; $j++) {
                $data['datasets'][$j]['data'][$i] = 0;
            }
        }
        return $data;
    }

    protected function chartjs_line_one_inited_with_time($beforebefore_tp, $step_secord, $stepNum) {
        return Variables::chartjs_line_inited_with_time(1, array('one'), $beforebefore_tp, $step_secord, $stepNum);
    }

    protected function chartjs_line_two_inited_with_time($beforebefore_tp, $step_secord, $stepNum, $one, $two) {
        return Variables::chartjs_line_inited_with_time(2, array($one, $two), $beforebefore_tp, $step_secord, $stepNum);
    }

    protected function chartjs_line_three_inited_with_time($beforebefore_tp, $step_secord, $stepNum, $one, $two, $three) {
        return Variables::chartjs_line_inited_with_time(3, array($one, $two, $three), $beforebefore_tp, $step_secord, $stepNum);
    }

    protected function chartjs_line_four_inited_with_time($beforebefore_tp, $step_secord, $stepNum, $one, $two, $three, $four) {
        return Variables::chartjs_line_inited_with_time(4, array($one, $two, $three, $four), $beforebefore_tp, $step_secord, $stepNum);
    }

    protected function chartjs_radar() {
        $data = array(
            'labels' => array(),
            'datasets' => array(
                array(
                    'label'=> 'radar',
                    'fillColor' => Variables::getcolor(90),
                    'strokeColor' => Variables::getcolor(0),
                    'pointColor' => Variables::getcolor(1),
                    'pointStrokeColor'=> "#fff",
                    'pointHighlightFill'=> "#fff",
                    'pointHighlightStroke'=> Variables::getcolor(0),
                    'data'=> array()
                ),
            )
        );
        return $data;
    }


    protected function timedist() {
        $secord_x = array(
            0,
            60, 120, 180, 240, 300, 360, 420, 480, 540, 600, #1-10
            1200, 1800, 2400, 3000, 3600, #11-15
            5400, 7200, #16, 17
            9000, 10800, 12600, 14400, 16200, 18000, 19800, 21600, 23400, 25200, #18-27
            27000, 28800, 30600, 32400, 34200, 36000, 37800, 39600, 41400, 43200, #28-37
            45000, 46800, 48600, 50400, 52200, 54000, 55800, 57600, 59400, 61200, #38-47
            63000, 64800, 66600, 68400, 70200, 72000, 73800, 75600, 77400, 79200, #48-57
            81000, 82800, 84600, 86400 #58-61
        );
        return $secord_x;
    }

    protected function timedist_getindex($time) {
        if ($time <= 600) {
            return ceil($time/60);
        }
        if ($time <= 3600) {
            return 9 + ceil($time/600);
        }
        if ($time <= 24 * 3600) {
            return 13 + ceil($time/1800);
        }
        $secord_x = Variables::timedist();
        return count($secord_x);
    }

    protected function secordtoHMS($time) {
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
        if ($time == '') {
            $time = '0';
        }
        return $time;
    }

    protected function secordtoHMSF($time) {
        $hour = (int)($time/3600);
        $minute = (int)($time%3600/60);
        $secord = (int)($time%60);
        if ($hour == 0) { $hour = '0H'; }
        else { $hour = (string)$hour . 'H'; }
        if ($minute == 0) { $minute = '0M'; }
        else { $minute = (string)$minute . 'M';}
        if ($secord == 0) { $secord = '0S'; }
        else {$secord = (string)$secord . 'S';}
        $time = $hour.$minute.$secord;
        if ($time == '') {
            $time = '0';
        }
        return $time;
    }

    protected function chartjs_line_one_inited_with_timedist($one) {
        $secord_x = Variables::timedist();
        $res = Variables::chartjs_line(1, array($one));
        foreach ($secord_x as $key => $time) {
            $time = Variables::secordtoHMS($time);
            $res['labels'][$key] = $time;
            $res['datasets'][0]['data'][$key] = 0;
        }
        $res['labels'][count($secord_x)] = 'MORE';
        $res['datasets'][0]['data'][count($secord_x)] = 0;
        return $res;
    }

    protected function chartjs_line_three_inited_with_timedist($one, $two, $three) {
        $secord_x = Variables::timedist();
        $res = Variables::chartjs_line_three($one, $two, $three);
        foreach ($secord_x as $key => $time) {
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
            $res['datasets'][1]['data'][$key] = 0;
            $res['datasets'][2]['data'][$key] = 0;
        }
        $res['labels'][count($secord_x)] = 'MORE';
        $res['datasets'][0]['data'][count($secord_x)] = 0;
        $res['datasets'][1]['data'][count($secord_x)] = 0;
        $res['datasets'][2]['data'][count($secord_x)] = 0;
        return $res;
    }

    protected function getStepNum() {
        return 60;
    }
    
    protected function chartjs_bar_one() {
        $data = array(
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
        return $data;
    }
}
