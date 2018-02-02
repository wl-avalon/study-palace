<?php
/**
 * Created by PhpStorm.
 * User: wzj-dev
 * Date: 18/2/1
 * Time: 下午7:09
 */

namespace app\constants;


class NodeConst
{
    public static $gradeNode = [
        ['parentNode' => 0, 'key' => 0, 'value' => '高中'],
        ['parentNode' => 0, 'key' => 1, 'value' => '初中'],
        ['parentNode' => 0, 'key' => 2, 'value' => '小学'],
    ];
    public static $subjectNode = [
        ['parentNode' => 0, 'key' => 0, 'value' => '语文'],
        ['parentNode' => 0, 'key' => 1, 'value' => '数学'],
        ['parentNode' => 0, 'key' => 2, 'value' => '英语'],
        ['parentNode' => 0, 'key' => 3, 'value' => '物理'],
        ['parentNode' => 0, 'key' => 4, 'value' => '化学'],
        ['parentNode' => 0, 'key' => 5, 'value' => '生物'],
        ['parentNode' => 0, 'key' => 6, 'value' => '政治'],
        ['parentNode' => 0, 'key' => 7, 'value' => '历史'],
        ['parentNode' => 0, 'key' => 8, 'value' => '地理'],
        ['parentNode' => 0, 'key' => 9, 'value' => '通用技术'],
        ['parentNode' => 0, 'key' => 10, 'value' => '信息技术'],
        ['parentNode' => 1, 'key' => 11, 'value' => '语文'],
        ['parentNode' => 1, 'key' => 12, 'value' => '数学'],
        ['parentNode' => 1, 'key' => 13, 'value' => '英语'],
        ['parentNode' => 1, 'key' => 14, 'value' => '物理'],
        ['parentNode' => 1, 'key' => 15, 'value' => '化学'],
        ['parentNode' => 1, 'key' => 16, 'value' => '生物'],
        ['parentNode' => 1, 'key' => 17, 'value' => '政治'],
        ['parentNode' => 1, 'key' => 18, 'value' => '历史'],
        ['parentNode' => 1, 'key' => 19, 'value' => '地理'],
        ['parentNode' => 2, 'key' => 20, 'value' => '语文'],
        ['parentNode' => 2, 'key' => 21, 'value' => '数学'],
    ];
    public static $versionNode = [
        ['subjectNode' => 0, 'key' => 0, 'value' => '人教版'],
        ['subjectNode' => 0, 'key' => 1, 'value' => '苏教版'],
        ['subjectNode' => 0, 'key' => 2, 'value' => '粤教版'],
        ['subjectNode' => 0, 'key' => 3, 'value' => '语文版'],
        ['subjectNode' => 0, 'key' => 4, 'value' => '鲁人版'],
        ['subjectNode' => 1, 'key' => 5, 'value' => '人教A版'],
        ['subjectNode' => 1, 'key' => 6, 'value' => '人教B版'],
        ['subjectNode' => 1, 'key' => 7, 'value' => '苏教版'],
        ['subjectNode' => 1, 'key' => 8, 'value' => '北师大版'],
        ['subjectNode' => 2, 'key' => 9, 'value' => '人教版'],
        ['subjectNode' => 2, 'key' => 10, 'value' => '外研版'],
        ['subjectNode' => 2, 'key' => 11, 'value' => '译林版'],
        ['subjectNode' => 2, 'key' => 12, 'value' => '北师大版'],
        ['subjectNode' => 2, 'key' => 13, 'value' => '重大版'],
        ['subjectNode' => 3, 'key' => 14, 'value' => '人教版'],
        ['subjectNode' => 3, 'key' => 15, 'value' => '鲁科版'],
        ['subjectNode' => 3, 'key' => 16, 'value' => '沪科版'],
        ['subjectNode' => 3, 'key' => 17, 'value' => '粤教版'],
        ['subjectNode' => 3, 'key' => 18, 'value' => '教科版'],
        ['subjectNode' => 4, 'key' => 19, 'value' => '人教版'],
        ['subjectNode' => 4, 'key' => 20, 'value' => '鲁科版'],
        ['subjectNode' => 4, 'key' => 21, 'value' => '苏教版'],
        ['subjectNode' => 5, 'key' => 22, 'value' => '人教版'],
        ['subjectNode' => 5, 'key' => 23, 'value' => '苏教版'],
        ['subjectNode' => 5, 'key' => 24, 'value' => '浙科版'],
        ['subjectNode' => 5, 'key' => 25, 'value' => '北师大版'],
        ['subjectNode' => 6, 'key' => 26, 'value' => '人教版'],
        ['subjectNode' => 7, 'key' => 27, 'value' => '人教版'],
        ['subjectNode' => 7, 'key' => 28, 'value' => '人民版'],
        ['subjectNode' => 7, 'key' => 29, 'value' => '岳麓版'],
        ['subjectNode' => 7, 'key' => 30, 'value' => '北师大版'],
        ['subjectNode' => 8, 'key' => 31, 'value' => '人教版'],
        ['subjectNode' => 8, 'key' => 32, 'value' => '湘教版'],
        ['subjectNode' => 8, 'key' => 33, 'value' => '鲁教版'],
        ['subjectNode' => 8, 'key' => 34, 'value' => '中图版'],
        ['subjectNode' => 9, 'key' => 35, 'value' => '苏教版'],
        ['subjectNode' => 10, 'key' => 36, 'value' => '浙教版'],
        ['subjectNode' => 11, 'key' => 37, 'value' => '人教版'],
        ['subjectNode' => 11, 'key' => 38, 'value' => '北师大版'],
        ['subjectNode' => 11, 'key' => 39, 'value' => '苏教版'],
        ['subjectNode' => 11, 'key' => 40, 'value' => '语文版'],
        ['subjectNode' => 11, 'key' => 41, 'value' => '冀教版'],
        ['subjectNode' => 11, 'key' => 42, 'value' => '鄂教版'],
        ['subjectNode' => 11, 'key' => 43, 'value' => '鲁教版'],
        ['subjectNode' => 12, 'key' => 44, 'value' => '人教版'],
        ['subjectNode' => 12, 'key' => 45, 'value' => '北师大版'],
        ['subjectNode' => 12, 'key' => 46, 'value' => '苏科版'],
        ['subjectNode' => 12, 'key' => 47, 'value' => '浙教版'],
        ['subjectNode' => 12, 'key' => 48, 'value' => '华师大版'],
        ['subjectNode' => 12, 'key' => 49, 'value' => '沪科版'],
        ['subjectNode' => 12, 'key' => 50, 'value' => '冀教版'],
        ['subjectNode' => 12, 'key' => 51, 'value' => '青岛版'],
        ['subjectNode' => 12, 'key' => 52, 'value' => '鲁教版'],
        ['subjectNode' => 13, 'key' => 53, 'value' => '人教版'],
        ['subjectNode' => 13, 'key' => 54, 'value' => '外研版'],
        ['subjectNode' => 13, 'key' => 55, 'value' => '译林版'],
        ['subjectNode' => 13, 'key' => 56, 'value' => '鲁教版'],
        ['subjectNode' => 14, 'key' => 57, 'value' => '人教版'],
        ['subjectNode' => 14, 'key' => 58, 'value' => '北师大版'],
        ['subjectNode' => 14, 'key' => 59, 'value' => '沪科版'],
        ['subjectNode' => 14, 'key' => 60, 'value' => '苏科版'],
        ['subjectNode' => 14, 'key' => 61, 'value' => '教科版'],
        ['subjectNode' => 14, 'key' => 62, 'value' => '鲁科版'],
        ['subjectNode' => 15, 'key' => 63, 'value' => '人教版'],
        ['subjectNode' => 15, 'key' => 64, 'value' => '粤教版'],
        ['subjectNode' => 15, 'key' => 65, 'value' => '沪教版'],
        ['subjectNode' => 15, 'key' => 66, 'value' => '仁爱版'],
        ['subjectNode' => 15, 'key' => 67, 'value' => '鲁教版'],
        ['subjectNode' => 16, 'key' => 68, 'value' => '人教版'],
        ['subjectNode' => 16, 'key' => 69, 'value' => '北师大版'],
        ['subjectNode' => 16, 'key' => 70, 'value' => '济南版'],
        ['subjectNode' => 16, 'key' => 71, 'value' => '苏教版'],
        ['subjectNode' => 16, 'key' => 72, 'value' => '苏科版'],
        ['subjectNode' => 16, 'key' => 73, 'value' => '冀教版'],
        ['subjectNode' => 17, 'key' => 74, 'value' => '人教版'],
        ['subjectNode' => 17, 'key' => 75, 'value' => '北师大版'],
        ['subjectNode' => 17, 'key' => 76, 'value' => '苏教版'],
        ['subjectNode' => 17, 'key' => 77, 'value' => '粤教版'],
        ['subjectNode' => 17, 'key' => 78, 'value' => '鲁人版'],
        ['subjectNode' => 17, 'key' => 79, 'value' => '人民版'],
        ['subjectNode' => 17, 'key' => 80, 'value' => '教科版'],
        ['subjectNode' => 17, 'key' => 81, 'value' => '湘教版'],
        ['subjectNode' => 17, 'key' => 82, 'value' => '陕教版'],
        ['subjectNode' => 18, 'key' => 83, 'value' => '人教版'],
        ['subjectNode' => 18, 'key' => 84, 'value' => '北师大版'],
        ['subjectNode' => 18, 'key' => 85, 'value' => '岳麓版'],
        ['subjectNode' => 18, 'key' => 86, 'value' => '川教版'],
        ['subjectNode' => 18, 'key' => 87, 'value' => '中华书局版'],
        ['subjectNode' => 19, 'key' => 88, 'value' => '人教版'],
        ['subjectNode' => 19, 'key' => 89, 'value' => '星球版'],
        ['subjectNode' => 19, 'key' => 90, 'value' => '中图版'],
        ['subjectNode' => 19, 'key' => 91, 'value' => '湘教版'],
        ['subjectNode' => 19, 'key' => 92, 'value' => '仁爱版'],
        ['subjectNode' => 20, 'key' => 93, 'value' => '人教版'],
        ['subjectNode' => 21, 'key' => 94, 'value' => '人教版'],
    ];
    public static $moduleNode = [
        ['versionNode' => 0, 'key' => 0, 'value' => '必修1'],
        ['versionNode' => 0, 'key' => 1, 'value' => '必修2'],
        ['versionNode' => 0, 'key' => 2, 'value' => '必修3'],
        ['versionNode' => 0, 'key' => 3, 'value' => '必修4'],
        ['versionNode' => 0, 'key' => 4, 'value' => '必修5'],
        ['versionNode' => 0, 'key' => 5, 'value' => '中国古代诗歌散文欣赏'],
        ['versionNode' => 0, 'key' => 6, 'value' => '中国小说欣赏'],
        ['versionNode' => 0, 'key' => 7, 'value' => '外国小说欣赏'],
        ['versionNode' => 0, 'key' => 8, 'value' => '一轮复习'],
        ['versionNode' => 1, 'key' => 9, 'value' => '必修1'],
        ['versionNode' => 1, 'key' => 10, 'value' => '必修2'],
        ['versionNode' => 1, 'key' => 11, 'value' => '必修3'],
        ['versionNode' => 1, 'key' => 12, 'value' => '必修4'],
        ['versionNode' => 1, 'key' => 13, 'value' => '必修5'],
        ['versionNode' => 1, 'key' => 14, 'value' => '唐诗宋词选读'],
        ['versionNode' => 1, 'key' => 15, 'value' => '现代散文选读'],
        ['versionNode' => 1, 'key' => 16, 'value' => '《史记》选读'],
        ['versionNode' => 1, 'key' => 17, 'value' => '一轮复习'],
        ['versionNode' => 2, 'key' => 18, 'value' => '必修1'],
        ['versionNode' => 2, 'key' => 19, 'value' => '必修2'],
        ['versionNode' => 2, 'key' => 20, 'value' => '必修3'],
        ['versionNode' => 2, 'key' => 21, 'value' => '必修4'],
        ['versionNode' => 2, 'key' => 22, 'value' => '必修5'],
        ['versionNode' => 2, 'key' => 23, 'value' => '一轮复习'],
        ['versionNode' => 3, 'key' => 24, 'value' => '必修1'],
        ['versionNode' => 3, 'key' => 25, 'value' => '必修2'],
        ['versionNode' => 3, 'key' => 26, 'value' => '必修3'],
        ['versionNode' => 3, 'key' => 27, 'value' => '必修4'],
        ['versionNode' => 3, 'key' => 28, 'value' => '必修5'],
        ['versionNode' => 3, 'key' => 29, 'value' => '一轮复习'],
        ['versionNode' => 4, 'key' => 30, 'value' => '必修1'],
        ['versionNode' => 4, 'key' => 31, 'value' => '必修2'],
        ['versionNode' => 4, 'key' => 32, 'value' => '必修3'],
        ['versionNode' => 4, 'key' => 33, 'value' => '必修4'],
        ['versionNode' => 4, 'key' => 34, 'value' => '必修5'],
        ['versionNode' => 4, 'key' => 35, 'value' => '一轮复习'],
        ['versionNode' => 5, 'key' => 36, 'value' => '必修1'],
        ['versionNode' => 5, 'key' => 37, 'value' => '必修2'],
        ['versionNode' => 5, 'key' => 38, 'value' => '必修3'],
        ['versionNode' => 5, 'key' => 39, 'value' => '必修4'],
        ['versionNode' => 5, 'key' => 40, 'value' => '必修5'],
        ['versionNode' => 5, 'key' => 41, 'value' => '选修1-1'],
        ['versionNode' => 5, 'key' => 42, 'value' => '选修1-2'],
        ['versionNode' => 5, 'key' => 43, 'value' => '选修2-1'],
        ['versionNode' => 5, 'key' => 44, 'value' => '选修2-2'],
        ['versionNode' => 5, 'key' => 45, 'value' => '选修2-3'],
        ['versionNode' => 5, 'key' => 46, 'value' => '一轮复习'],
        ['versionNode' => 5, 'key' => 47, 'value' => '二轮复习'],
        ['versionNode' => 6, 'key' => 48, 'value' => '必修1'],
        ['versionNode' => 6, 'key' => 49, 'value' => '必修2'],
        ['versionNode' => 6, 'key' => 50, 'value' => '必修3'],
        ['versionNode' => 6, 'key' => 51, 'value' => '必修4'],
        ['versionNode' => 6, 'key' => 52, 'value' => '必修5'],
        ['versionNode' => 6, 'key' => 53, 'value' => '选修1-1'],
        ['versionNode' => 6, 'key' => 54, 'value' => '选修1-2'],
        ['versionNode' => 6, 'key' => 55, 'value' => '选修2-1'],
        ['versionNode' => 6, 'key' => 56, 'value' => '选修2-2'],
        ['versionNode' => 6, 'key' => 57, 'value' => '选修2-3'],
        ['versionNode' => 6, 'key' => 58, 'value' => '一轮复习'],
        ['versionNode' => 6, 'key' => 59, 'value' => '二轮专题'],
        ['versionNode' => 7, 'key' => 60, 'value' => '必修1'],
        ['versionNode' => 7, 'key' => 61, 'value' => '必修2'],
        ['versionNode' => 7, 'key' => 62, 'value' => '必修3'],
        ['versionNode' => 7, 'key' => 63, 'value' => '必修4'],
        ['versionNode' => 7, 'key' => 64, 'value' => '必修5'],
        ['versionNode' => 7, 'key' => 65, 'value' => '选修1-1'],
        ['versionNode' => 7, 'key' => 66, 'value' => '选修1-2'],
        ['versionNode' => 7, 'key' => 67, 'value' => '选修2-1'],
        ['versionNode' => 7, 'key' => 68, 'value' => '选修2-2'],
        ['versionNode' => 7, 'key' => 69, 'value' => '选修2-3'],
        ['versionNode' => 7, 'key' => 70, 'value' => '一轮复习'],
        ['versionNode' => 7, 'key' => 71, 'value' => '二轮专题'],
        ['versionNode' => 8, 'key' => 72, 'value' => '必修1'],
        ['versionNode' => 8, 'key' => 73, 'value' => '必修2'],
        ['versionNode' => 8, 'key' => 74, 'value' => '必修3'],
        ['versionNode' => 8, 'key' => 75, 'value' => '必修4'],
        ['versionNode' => 8, 'key' => 76, 'value' => '必修5'],
        ['versionNode' => 8, 'key' => 77, 'value' => '选修1-1'],
        ['versionNode' => 8, 'key' => 78, 'value' => '选修1-2'],
        ['versionNode' => 8, 'key' => 79, 'value' => '选修2-1'],
        ['versionNode' => 8, 'key' => 80, 'value' => '选修2-2'],
        ['versionNode' => 8, 'key' => 81, 'value' => '选修2-3'],
        ['versionNode' => 8, 'key' => 82, 'value' => '一轮复习'],
        ['versionNode' => 8, 'key' => 83, 'value' => '二轮专题'],
        ['versionNode' => 9, 'key' => 84, 'value' => '必修1'],
        ['versionNode' => 9, 'key' => 85, 'value' => '必修2'],
        ['versionNode' => 9, 'key' => 86, 'value' => '必修3'],
        ['versionNode' => 9, 'key' => 87, 'value' => '必修4'],
        ['versionNode' => 9, 'key' => 88, 'value' => '必修5'],
        ['versionNode' => 9, 'key' => 89, 'value' => '选修6'],
        ['versionNode' => 9, 'key' => 90, 'value' => '选修7'],
        ['versionNode' => 9, 'key' => 91, 'value' => '选修8'],
        ['versionNode' => 9, 'key' => 92, 'value' => '一轮复习'],
        ['versionNode' => 10, 'key' => 93, 'value' => '必修1'],
        ['versionNode' => 10, 'key' => 94, 'value' => '必修2'],
        ['versionNode' => 10, 'key' => 95, 'value' => '必修3'],
        ['versionNode' => 10, 'key' => 96, 'value' => '必修4'],
        ['versionNode' => 10, 'key' => 97, 'value' => '必修5'],
        ['versionNode' => 10, 'key' => 98, 'value' => '选修6'],
        ['versionNode' => 10, 'key' => 99, 'value' => '选修7'],
        ['versionNode' => 10, 'key' => 100, 'value' => '选修8'],
        ['versionNode' => 10, 'key' => 101, 'value' => '一轮复习'],
        ['versionNode' => 11, 'key' => 102, 'value' => '必修1'],
        ['versionNode' => 11, 'key' => 103, 'value' => '必修2'],
        ['versionNode' => 11, 'key' => 104, 'value' => '必修3'],
        ['versionNode' => 11, 'key' => 105, 'value' => '必修4'],
        ['versionNode' => 11, 'key' => 106, 'value' => '必修5'],
        ['versionNode' => 11, 'key' => 107, 'value' => '选修6'],
        ['versionNode' => 11, 'key' => 108, 'value' => '选修7'],
        ['versionNode' => 11, 'key' => 109, 'value' => '选修8'],
        ['versionNode' => 11, 'key' => 110, 'value' => '一轮复习'],
        ['versionNode' => 12, 'key' => 111, 'value' => '必修1'],
        ['versionNode' => 12, 'key' => 112, 'value' => '必修2'],
        ['versionNode' => 12, 'key' => 113, 'value' => '必修3'],
        ['versionNode' => 12, 'key' => 114, 'value' => '必修4'],
        ['versionNode' => 12, 'key' => 115, 'value' => '必修5'],
        ['versionNode' => 12, 'key' => 116, 'value' => '选修6'],
        ['versionNode' => 12, 'key' => 117, 'value' => '选修7'],
        ['versionNode' => 12, 'key' => 118, 'value' => '选修8'],
        ['versionNode' => 12, 'key' => 119, 'value' => '一轮复习'],
        ['versionNode' => 13, 'key' => 120, 'value' => '必修1'],
        ['versionNode' => 13, 'key' => 121, 'value' => '必修2'],
        ['versionNode' => 13, 'key' => 122, 'value' => '必修3'],
        ['versionNode' => 13, 'key' => 123, 'value' => '必修4'],
        ['versionNode' => 13, 'key' => 124, 'value' => '必修5'],
        ['versionNode' => 13, 'key' => 125, 'value' => '选修6'],
        ['versionNode' => 13, 'key' => 126, 'value' => '选修7'],
        ['versionNode' => 13, 'key' => 127, 'value' => '选修8'],
        ['versionNode' => 13, 'key' => 128, 'value' => '一轮复习'],
        ['versionNode' => 14, 'key' => 129, 'value' => '必修1'],
        ['versionNode' => 14, 'key' => 130, 'value' => '必修2'],
        ['versionNode' => 14, 'key' => 131, 'value' => '选修3-1'],
        ['versionNode' => 14, 'key' => 132, 'value' => '选修3-2'],
        ['versionNode' => 14, 'key' => 133, 'value' => '选修3-3'],
        ['versionNode' => 14, 'key' => 134, 'value' => '选修3-4'],
        ['versionNode' => 14, 'key' => 135, 'value' => '选修3-5'],
        ['versionNode' => 14, 'key' => 136, 'value' => '一轮复习'],
        ['versionNode' => 14, 'key' => 137, 'value' => '二轮复习'],
        ['versionNode' => 15, 'key' => 138, 'value' => '必修1'],
        ['versionNode' => 15, 'key' => 139, 'value' => '必修2'],
        ['versionNode' => 15, 'key' => 140, 'value' => '选修3-1'],
        ['versionNode' => 15, 'key' => 141, 'value' => '选修3-2'],
        ['versionNode' => 15, 'key' => 142, 'value' => '选修3-3'],
        ['versionNode' => 15, 'key' => 143, 'value' => '选修3-4'],
        ['versionNode' => 15, 'key' => 144, 'value' => '选修3-5'],
        ['versionNode' => 15, 'key' => 145, 'value' => '一轮复习'],
        ['versionNode' => 15, 'key' => 146, 'value' => '二轮复习'],
        ['versionNode' => 16, 'key' => 147, 'value' => '必修1'],
        ['versionNode' => 16, 'key' => 148, 'value' => '必修2'],
        ['versionNode' => 16, 'key' => 149, 'value' => '选修3-1'],
        ['versionNode' => 16, 'key' => 150, 'value' => '选修3-2'],
        ['versionNode' => 16, 'key' => 151, 'value' => '选修3-4'],
        ['versionNode' => 16, 'key' => 152, 'value' => '选修3-5'],
        ['versionNode' => 16, 'key' => 153, 'value' => '一轮复习'],
        ['versionNode' => 16, 'key' => 154, 'value' => '二轮复习'],
        ['versionNode' => 17, 'key' => 155, 'value' => '必修1'],
        ['versionNode' => 17, 'key' => 156, 'value' => '必修2'],
        ['versionNode' => 17, 'key' => 157, 'value' => '选修3-1'],
        ['versionNode' => 17, 'key' => 158, 'value' => '选修3-2'],
        ['versionNode' => 17, 'key' => 159, 'value' => '选修3-3'],
        ['versionNode' => 17, 'key' => 160, 'value' => '选修3-5'],
        ['versionNode' => 17, 'key' => 161, 'value' => '一轮复习'],
        ['versionNode' => 17, 'key' => 162, 'value' => '二轮复习'],
        ['versionNode' => 18, 'key' => 163, 'value' => '必修1'],
        ['versionNode' => 18, 'key' => 164, 'value' => '必修2'],
        ['versionNode' => 18, 'key' => 165, 'value' => '选修3-1'],
        ['versionNode' => 18, 'key' => 166, 'value' => '选修3-2'],
        ['versionNode' => 18, 'key' => 167, 'value' => '选修3-3'],
        ['versionNode' => 18, 'key' => 168, 'value' => '选修3-4'],
        ['versionNode' => 18, 'key' => 169, 'value' => '选修3-5'],
        ['versionNode' => 18, 'key' => 170, 'value' => '一轮复习'],
        ['versionNode' => 18, 'key' => 171, 'value' => '二轮复习'],
        ['versionNode' => 19, 'key' => 172, 'value' => '必修1'],
        ['versionNode' => 19, 'key' => 173, 'value' => '必修2'],
        ['versionNode' => 19, 'key' => 174, 'value' => '物质结构与性质'],
        ['versionNode' => 19, 'key' => 175, 'value' => '化学反应原理'],
        ['versionNode' => 19, 'key' => 176, 'value' => '有机化学基础'],
        ['versionNode' => 19, 'key' => 177, 'value' => '一轮复习'],
        ['versionNode' => 20, 'key' => 178, 'value' => '必修1'],
        ['versionNode' => 20, 'key' => 179, 'value' => '必修2'],
        ['versionNode' => 20, 'key' => 180, 'value' => '物质结构与性质'],
        ['versionNode' => 20, 'key' => 181, 'value' => '化学反应原理'],
        ['versionNode' => 20, 'key' => 182, 'value' => '有机化学基础'],
        ['versionNode' => 20, 'key' => 183, 'value' => '一轮复习'],
        ['versionNode' => 21, 'key' => 184, 'value' => '必修1'],
        ['versionNode' => 21, 'key' => 185, 'value' => '必修2'],
        ['versionNode' => 21, 'key' => 186, 'value' => '物质结构与性质'],
        ['versionNode' => 21, 'key' => 187, 'value' => '化学反应原理'],
        ['versionNode' => 21, 'key' => 188, 'value' => '有机化学基础'],
        ['versionNode' => 21, 'key' => 189, 'value' => '一轮复习'],
        ['versionNode' => 22, 'key' => 190, 'value' => '必修1'],
        ['versionNode' => 22, 'key' => 191, 'value' => '必修2'],
        ['versionNode' => 22, 'key' => 192, 'value' => '必修3'],
        ['versionNode' => 22, 'key' => 193, 'value' => '选修1'],
        ['versionNode' => 22, 'key' => 194, 'value' => '选修3'],
        ['versionNode' => 22, 'key' => 195, 'value' => '一轮复习'],
        ['versionNode' => 23, 'key' => 196, 'value' => '必修1'],
        ['versionNode' => 23, 'key' => 197, 'value' => '必修2'],
        ['versionNode' => 23, 'key' => 198, 'value' => '必修3'],
        ['versionNode' => 23, 'key' => 199, 'value' => '选修1'],
        ['versionNode' => 23, 'key' => 200, 'value' => '选修3'],
        ['versionNode' => 23, 'key' => 201, 'value' => '一轮复习'],
        ['versionNode' => 24, 'key' => 202, 'value' => '必修1'],
        ['versionNode' => 24, 'key' => 203, 'value' => '必修2'],
        ['versionNode' => 24, 'key' => 204, 'value' => '必修3'],
        ['versionNode' => 24, 'key' => 205, 'value' => '选修1'],
        ['versionNode' => 24, 'key' => 206, 'value' => '选修3'],
        ['versionNode' => 24, 'key' => 207, 'value' => '一轮复习'],
        ['versionNode' => 25, 'key' => 208, 'value' => '必修1'],
        ['versionNode' => 25, 'key' => 209, 'value' => '必修2'],
        ['versionNode' => 25, 'key' => 210, 'value' => '必修3'],
        ['versionNode' => 25, 'key' => 211, 'value' => '选修1'],
        ['versionNode' => 25, 'key' => 212, 'value' => '选修3'],
        ['versionNode' => 25, 'key' => 213, 'value' => '一轮复习'],
        ['versionNode' => 26, 'key' => 214, 'value' => '必修1'],
        ['versionNode' => 26, 'key' => 215, 'value' => '必修2'],
        ['versionNode' => 26, 'key' => 216, 'value' => '必修3'],
        ['versionNode' => 26, 'key' => 217, 'value' => '必修4'],
        ['versionNode' => 26, 'key' => 218, 'value' => '选修2'],
        ['versionNode' => 26, 'key' => 219, 'value' => '选修3'],
        ['versionNode' => 26, 'key' => 220, 'value' => '选修4'],
        ['versionNode' => 26, 'key' => 221, 'value' => '选修5'],
        ['versionNode' => 26, 'key' => 222, 'value' => '选修6'],
        ['versionNode' => 26, 'key' => 223, 'value' => '一轮复习'],
        ['versionNode' => 27, 'key' => 224, 'value' => '必修1'],
        ['versionNode' => 27, 'key' => 225, 'value' => '必修2'],
        ['versionNode' => 27, 'key' => 226, 'value' => '必修3'],
        ['versionNode' => 27, 'key' => 227, 'value' => '选修1'],
        ['versionNode' => 27, 'key' => 228, 'value' => '选修2'],
        ['versionNode' => 27, 'key' => 229, 'value' => '选修3'],
        ['versionNode' => 27, 'key' => 230, 'value' => '选修4'],
        ['versionNode' => 27, 'key' => 231, 'value' => '选修6'],
        ['versionNode' => 27, 'key' => 232, 'value' => '一轮复习'],
        ['versionNode' => 28, 'key' => 233, 'value' => '必修第一册'],
        ['versionNode' => 28, 'key' => 234, 'value' => '必修第二册'],
        ['versionNode' => 28, 'key' => 235, 'value' => '必修第三册'],
        ['versionNode' => 28, 'key' => 236, 'value' => '选修1'],
        ['versionNode' => 28, 'key' => 237, 'value' => '选修2'],
        ['versionNode' => 28, 'key' => 238, 'value' => '选修3'],
        ['versionNode' => 28, 'key' => 239, 'value' => '选修4'],
        ['versionNode' => 28, 'key' => 240, 'value' => '一轮复习'],
        ['versionNode' => 29, 'key' => 241, 'value' => '必修Ⅰ'],
        ['versionNode' => 29, 'key' => 242, 'value' => '必修Ⅱ'],
        ['versionNode' => 29, 'key' => 243, 'value' => '必修Ⅲ'],
        ['versionNode' => 29, 'key' => 244, 'value' => '选修1'],
        ['versionNode' => 29, 'key' => 245, 'value' => '选修2'],
        ['versionNode' => 29, 'key' => 246, 'value' => '选修3'],
        ['versionNode' => 29, 'key' => 247, 'value' => '选修4'],
        ['versionNode' => 29, 'key' => 248, 'value' => '一轮复习'],
        ['versionNode' => 30, 'key' => 249, 'value' => '必修1'],
        ['versionNode' => 30, 'key' => 250, 'value' => '必修2'],
        ['versionNode' => 30, 'key' => 251, 'value' => '必修3'],
        ['versionNode' => 30, 'key' => 252, 'value' => '选修1'],
        ['versionNode' => 30, 'key' => 253, 'value' => '选修2'],
        ['versionNode' => 30, 'key' => 254, 'value' => '选修3'],
        ['versionNode' => 30, 'key' => 255, 'value' => '选修4'],
        ['versionNode' => 30, 'key' => 256, 'value' => '一轮复习'],
        ['versionNode' => 31, 'key' => 257, 'value' => '必修1'],
        ['versionNode' => 31, 'key' => 258, 'value' => '必修2'],
        ['versionNode' => 31, 'key' => 259, 'value' => '必修3'],
        ['versionNode' => 31, 'key' => 260, 'value' => '选修2'],
        ['versionNode' => 31, 'key' => 261, 'value' => '选修3'],
        ['versionNode' => 31, 'key' => 262, 'value' => '选修4'],
        ['versionNode' => 31, 'key' => 263, 'value' => '选修5'],
        ['versionNode' => 31, 'key' => 264, 'value' => '选修6'],
        ['versionNode' => 31, 'key' => 265, 'value' => '区域地理'],
        ['versionNode' => 31, 'key' => 266, 'value' => '一轮复习'],
        ['versionNode' => 32, 'key' => 267, 'value' => '必修1'],
        ['versionNode' => 32, 'key' => 268, 'value' => '必修2'],
        ['versionNode' => 32, 'key' => 269, 'value' => '必修3'],
        ['versionNode' => 32, 'key' => 270, 'value' => '选修2'],
        ['versionNode' => 32, 'key' => 271, 'value' => '选修3'],
        ['versionNode' => 32, 'key' => 272, 'value' => '选修4'],
        ['versionNode' => 32, 'key' => 273, 'value' => '选修5'],
        ['versionNode' => 32, 'key' => 274, 'value' => '选修6'],
        ['versionNode' => 32, 'key' => 275, 'value' => '区域地理'],
        ['versionNode' => 32, 'key' => 276, 'value' => '一轮复习'],
        ['versionNode' => 33, 'key' => 277, 'value' => '必修1'],
        ['versionNode' => 33, 'key' => 278, 'value' => '必修2'],
        ['versionNode' => 33, 'key' => 279, 'value' => '必修3'],
        ['versionNode' => 33, 'key' => 280, 'value' => '选修2'],
        ['versionNode' => 33, 'key' => 281, 'value' => '选修3'],
        ['versionNode' => 33, 'key' => 282, 'value' => '选修4'],
        ['versionNode' => 33, 'key' => 283, 'value' => '选修5'],
        ['versionNode' => 33, 'key' => 284, 'value' => '选修6'],
        ['versionNode' => 33, 'key' => 285, 'value' => '区域地理'],
        ['versionNode' => 33, 'key' => 286, 'value' => '一轮复习'],
        ['versionNode' => 34, 'key' => 287, 'value' => '必修1'],
        ['versionNode' => 34, 'key' => 288, 'value' => '必修2'],
        ['versionNode' => 34, 'key' => 289, 'value' => '必修3'],
        ['versionNode' => 34, 'key' => 290, 'value' => '选修2'],
        ['versionNode' => 34, 'key' => 291, 'value' => '选修3'],
        ['versionNode' => 34, 'key' => 292, 'value' => '选修4'],
        ['versionNode' => 34, 'key' => 293, 'value' => '选修5'],
        ['versionNode' => 34, 'key' => 294, 'value' => '选修6'],
        ['versionNode' => 34, 'key' => 295, 'value' => '区域地理'],
        ['versionNode' => 34, 'key' => 296, 'value' => '一轮复习'],
        ['versionNode' => 37, 'key' => 297, 'value' => '七年级上册（新版）'],
        ['versionNode' => 37, 'key' => 298, 'value' => '七年级下册（新版）'],
        ['versionNode' => 37, 'key' => 299, 'value' => '七年级上册（旧版）'],
        ['versionNode' => 37, 'key' => 300, 'value' => '七年级下册（旧版）'],
        ['versionNode' => 37, 'key' => 301, 'value' => '八年级上册'],
        ['versionNode' => 37, 'key' => 302, 'value' => '八年级下册'],
        ['versionNode' => 37, 'key' => 303, 'value' => '九年级上册'],
        ['versionNode' => 37, 'key' => 304, 'value' => '九年级下册'],
        ['versionNode' => 44, 'key' => 305, 'value' => '七年级上册'],
        ['versionNode' => 44, 'key' => 306, 'value' => '七年级下册'],
        ['versionNode' => 44, 'key' => 307, 'value' => '八年级上册'],
        ['versionNode' => 44, 'key' => 308, 'value' => '八年级下册'],
        ['versionNode' => 44, 'key' => 309, 'value' => '九年级上册'],
        ['versionNode' => 44, 'key' => 310, 'value' => '九年级下册'],
        ['versionNode' => 53, 'key' => 311, 'value' => '七年级上册'],
        ['versionNode' => 53, 'key' => 312, 'value' => '八年级上册'],
        ['versionNode' => 57, 'key' => 313, 'value' => '八年级上册'],
        ['versionNode' => 57, 'key' => 314, 'value' => '八年级下册'],
        ['versionNode' => 57, 'key' => 315, 'value' => '九年级全一册'],
        ['versionNode' => 63, 'key' => 316, 'value' => '九年级上册'],
        ['versionNode' => 63, 'key' => 317, 'value' => '九年级下册'],
        ['versionNode' => 68, 'key' => 318, 'value' => '七年级上册'],
        ['versionNode' => 74, 'key' => 319, 'value' => '七年级上册'],
        ['versionNode' => 74, 'key' => 320, 'value' => '七年级下册'],
        ['versionNode' => 74, 'key' => 321, 'value' => '八年级上册'],
        ['versionNode' => 74, 'key' => 322, 'value' => '九年级全一册'],
        ['versionNode' => 83, 'key' => 323, 'value' => '七年级上册'],
        ['versionNode' => 83, 'key' => 324, 'value' => '七年级下册'],
        ['versionNode' => 83, 'key' => 325, 'value' => '八年级上册'],
        ['versionNode' => 83, 'key' => 326, 'value' => '八年级下册'],
        ['versionNode' => 83, 'key' => 327, 'value' => '九年级上册'],
        ['versionNode' => 84, 'key' => 328, 'value' => '七年级上册'],
        ['versionNode' => 84, 'key' => 329, 'value' => '七年级下册'],
        ['versionNode' => 88, 'key' => 330, 'value' => '七年级上册'],
        ['versionNode' => 88, 'key' => 331, 'value' => '七年级下册'],
        ['versionNode' => 88, 'key' => 332, 'value' => '八年级上册'],
        ['versionNode' => 88, 'key' => 333, 'value' => '八年级下册'],
        ['versionNode' => 93, 'key' => 334, 'value' => '模块'],
        ['versionNode' => 94, 'key' => 335, 'value' => '模块'],

    ];
}