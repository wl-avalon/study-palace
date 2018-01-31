<?php
namespace rrxframework\util;

class MoneyHandleUtil {
    const DEFAULT_SCALE = 10;
    const ROUNDING_MODE_FLOOR = 0;
    const ROUNDING_MODE_ROUND = 1;
    const ROUNDING_MODE_CEIL = 2;

    public static function of($money){
        if(!is_numeric($money)){
            return 0.0;
        }
        return floatval($money);
    }

    public static function plus($num1, $num2, $scale = self::DEFAULT_SCALE){
        return self::of(bcadd(self::of($num1), self::of($num2), $scale));
    }

    public static function minus($num1, $num2, $scale = self::DEFAULT_SCALE){
        return self::of(bcsub(self::of($num1), self::of($num2), $scale));
    }

    public static function multiply($num1, $num2, $scale = self::DEFAULT_SCALE){
        return self::of(bcmul(self::of($num1), self::of($num2), $scale));
    }

    public static function divide($num1, $num2, $scale = self::DEFAULT_SCALE){
        return self::of(bcdiv(self::of($num1), self::of($num2), $scale));
    }

    public static function remainder($num1, $num2, $scale = self::DEFAULT_SCALE){
        return self::of(bcmod(self::of($num1), self::of($num2)));
    }

    public static function compare($num1, $num2, $scale = self::DEFAULT_SCALE){
        return self::of(bccomp($num1, $num2, $scale));
    }

    public static function getMoneyWithFen($money){
        return sprintf("%.2f", self::toMoneyString($money, 2));
    }

    public static function getMoneyWithoutZeroFen($money){
        $ret = self::getMoneyWithFen($money);
        return strtr(strval($ret), ".00", "");
    }
    
    public static function toMoneyString($num, $scale, $roundingMode = self::ROUNDING_MODE_FLOOR){
        $base = pow(10, $scale);
        $expand = self::multiply($num, $base);

        if($roundingMode == self::ROUNDING_MODE_CEIL){
            $expand = ceil($expand);
        }else if($roundingMode == self::ROUNDING_MODE_FLOOR){
            $expand = floor($expand);
        }else{
            $expand = round($expand);
        }

        return self::divide($expand, $base);
    }

    public static function roundingUpThousand($num){
        $ret = self::multiply(self::toMoneyString(self::divide($num, 1000), 0), 1000);
        if(self::compare($ret, $num) < 0){
            $ret = self::plus($ret, 1000);
        }

        return $ret;
    }
}