<?php

namespace App\Helper;

class Calculator
{
    private int $timestamp = 0;
    private static $instance = null;

    public function __construct($date = null)
    {
        $this->timestamp = is_null($date) ? time() : strtotime($date);
    }

    /**
     * Повертає єдиний екземпляр класу Database. При створенні об'єкту класу, відбувається з'єднання з базою даних через розширення PDO.
     * Якщо з'єднання неможливо встановити, виводиться повідомлення про помилку.
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function settings(array $options = []): void
    {
        foreach ($options as $key => $value) {
            switch ($key) {
                case "date":
                    $this->timestamp = strtotime($value);
                    break;
                default:
            }
        }
    }

    public function getDate($format = "Y-m-d", $arr=[])
    {
        return date($format, $this->timestamp);
    }
    public function getDateArr($y=true,$m=false,$d=false): array
    {
        if($y) $arr["y"] = date($y, $this->timestamp);
        if($m) $arr["m"] = date($m, $this->timestamp);
        if($d) $arr["d"] = date($d, $this->timestamp);

        return $arr;
    }

    public function getDaysCountInMonth()
    {
        return date('t',$this->timestamp);
    }

    /**
     * @param string|null $date1
     * @param string|null $date2
     * @throws \Exception
     */
    public
    function getDateDiff(string $date1 = null, string $date2 = null, string $format = null)
    {
        $d1 = isset($date1) ? new \DateTime($date1) : new \DateTime("now");
        $d2 = isset($date2) ? new \DateTime($date2) : new \DateTime("now");

        $interval = date_diff($d1, $d2);

        return is_null($format) ? $interval : $interval->format($format);
    }

    public
    function getPercent(int $part, int $total, int $round = null): float
    {
        return $total == 0 || $part == 0 ? 0 : round($part / $total * 100, $round);
    }

}