<?php
namespace rrxframework\util;
use Yii;

/**
 * 数组工具类
 * 
 * @author hank-m
 */
class ArrayUtil
{
    /**
     * 二维数组多元素排序
     * @param array $old_arr 待排序数组
     * @param array $arr1 排序因子＋排序方式
     * @param array $arr2 排序因子＋排序方式
     * @return array
     * $arr = array(
     * array('id'=>1,'sort'=>1,'date'=>'1405648791'),
     * array('id'=>2,'sort'=>1,'date'=>'1405649791'),
     * array('id'=>3,'sort'=>2,'date'=>'1405647791'),
     * );
     * $arr = my_sort($arr, 'date');
     * $arr = my_sort($arr, 'sort');
     */
     public static function my_sort($arrays, $sort_key, $sort_order = SORT_ASC, $sort_type = SORT_NUMERIC) {
         $key_arrays = [];
        if(is_array($arrays)){
            foreach ($arrays as $k => $array){
                if (is_array($array)) {
                    $key_arrays[$k] = ($sort_type == SORT_NUMERIC) ? intval($array[$sort_key]) : $array[$sort_key];
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
        array_multisort($key_arrays, $sort_order, $sort_type, $arrays);
        return $arrays;
    }
    
    /**
     * 对相同的字段再根据另外的key排序
     * @param array $arr
     * @param string $equel_key
     * @param string $sort_key
     * @param string $sort_order
     * @param string $sort_type
     * @return array|boolean
     */
    public static function equalSort($arr, $equel_key, $sort_key, $sort_order = SORT_ASC, $sort_type = SORT_NUMERIC) {
        if (is_array($arr)) {
            $sortArr = $tempArr = $sortedArr = [];
            foreach ($arr as $k => &$val) {
                if (isset($arr[$k+1][$equel_key]) && $val[$equel_key] == $arr[$k+1][$equel_key]) {
                    $sortArr[$k] = $val;
                    $sortArr[$k+1] = $arr[$k+1];
                } else {
                    if (!empty($sortArr)) {
                        $sortedArr = self::my_sort($sortArr, $sort_key, SORT_DESC);
                        foreach ($sortedArr as &$val) {
                            $tempArr[] = $val;
                        }
                        $sortedArr = $sortArr = [];
                    } else {
                        $tempArr[] = $val;
                    }
                }
            } 
            return $tempArr;
        } else {
            return false;
        }
    } 
    
    /**
     * 二维数组某一列去重
     * @param array $arr
     * @param string $key
     * @return array
     */
    public static function assoc_unique($arr, $key = 'id')
    {
        if (empty($arr)) {
            return [];
        }
        $tmp_arr = array();
        $arr = array_reverse($arr, false);
        foreach($arr as $k => $v)
        {
            if(in_array($v[$key],$tmp_arr))//搜索$v[$key]是否在$tmp_arr数组中存在，若存在返回true
            {
                unset($arr[$k]);
            }
            else {
                $tmp_arr[] = $v[$key];
            }
        }
        sort($arr); //sort函数对数组进行排序
        return array_reverse($arr, false);
    }
    
    
    public static function array2index(array $arr, $key){
    	$ret = [];
    	foreach($arr as $v){
    		if(isset($v[$key])){
    			$ret[$v[$key]] = $v; 
    		}
    	}
    	return $ret;
    }
}




