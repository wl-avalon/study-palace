<?php
/**
 * Created by PhpStorm.
 * User: wzj-dev
 * Date: 18/1/31
 * Time: 下午7:57
 */
namespace app\constants;

class QuestionDetailBeanConst
{
    const DEL_STATUS_NORMAL = 0;
    const DEL_STATUS_DELETE = 1;

    public static $subjectChineseMapToEnum = [
        '语文'        => 'question_question_chinese',
        '数学'        => 'question_question_math',
        '英语'        => 'question_question_english',
        '物理'        => 'question_question_physical',
        '化学'        => 'question_question_chemistry',
        '生物'        => 'question_question_biological',
        '政治'        => 'question_question_political',
        '历史'        => 'question_question_history',
        '地理'        => 'question_question_geography',
        '通用技术'     => 'question_question_common_technology',
        '信息技术'     => 'question_question_internet_technology',
    ];
}