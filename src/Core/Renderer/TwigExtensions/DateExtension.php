<?php

namespace Src\Core\Renderer\TwigExtensions;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class DateExtension extends AbstractExtension
{

    public function getFunctions()
    {

        return [
        new TwigFunction('time_ago', [$this, 'timeAgo'], ['is_safe' => ['html']]),
        new TwigFunction('str_to_time', [$this, 'strToTime'], ['is_safe' => ['html']])
        ];
    }

    public function strToTime(string $date) :string
    {
        return strtotime($date);
    }

    function timeAgo(int $time)
    {
        $time_difference = time() - $time;

        if ($time_difference < 1) {
            return 'less than 1 second ago';
        }
        $condition = array(
        12 * 30 * 24 * 60 * 60  =>  'year',
        30 * 24 * 60 * 60       =>  'month',
        24 * 60 * 60            =>  'day',
        60 * 60                 =>  'hour',
        60                      =>  'minute',
        1                       =>  'second'
        );

        foreach ($condition as $secs => $str) {
            $d = $time_difference / $secs;

            if ($d >= 1) {
                $t = round($d);
                return $t . ' ' . $str . ($t > 1 ? 's' : '') . ' ago';
            }
        }
    }
}