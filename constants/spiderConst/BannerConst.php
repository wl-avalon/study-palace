<?php
/**
 * Created by PhpStorm.
 * User: wzj-dev
 * Date: 18/1/12
 * Time: 下午9:39
 */

namespace app\spiderConst;


class BannerConst
{
    public static $enumToSchoolSection = [      //学段枚举值
        3   => '高中',
        5   => '初中',
        239 => '小学',
    ];

    public static $enumToHighSchoolSubject = [  //高中学段枚举值
        9   => '语文',
        11  => '数学',
        13  => '英语',
        15  => '物理',
        17  => '化学',
        19  => '生物',
        21  => '政治',
        23  => '历史',
        25  => '地理',
        231 => '通用技术',
        235 => '信息技术',
    ];
}