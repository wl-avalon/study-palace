<?php
/**
 * Created by PhpStorm.
 * User: wzj-dev
 * Date: 18/1/15
 * Time: 下午9:47
 */
namespace app\components;

class Format
{
    /**
     * 获取页面tab映射
     * @param $response
     * @param $chooseLevel
     * @return mixed
     */
    public static function getLevelMap($response, $chooseLevel){
        $matchStr = [];
        preg_match_all("/\B<a[^>]*?aclick\(([1234]),([0-9]*)\)[^>]*?>([^<]*?)<\/a>\B/", $response, $matchStr);
        $tabLevelList   = $matchStr[1];
        $keyList        = $matchStr[2];
        $valueList      = $matchStr[3];
        $tabLevelMap = [];
        foreach($tabLevelList as $index => $level){
            $tabLevelMap[$level][$keyList[$index]] = $valueList[$index] ?? [];
        }
        return $tabLevelMap[$chooseLevel] ?? [];
    }

    /**
     * 获取题型映射
     * @param $response
     * @return array
     */
    public static function getTiXingMap($response){
        $matchStr = [];
        preg_match_all("/\B<a[^>]*?tixingclick\(([0-9]*)\)[^>]*?>([^<]*?)<\/a>\B/", $response, $matchStr);
        $tiXingIndexList    = $matchStr[1];
        $tiXingValueList    = $matchStr[2];

        $tiXingMap = [];
        foreach($tiXingIndexList as $tiXingIndex => $tiXingValue){
            $tiXingMap[$tiXingValue] = $tiXingValueList[$tiXingIndex];
        }
        return $tiXingMap;
    }

    /**
     * 获取难度映射
     * @param $response
     * @return array
     */
    public static function getNanDuMap($response){
        $matchStr = [];
        preg_match_all("/\B<a[^>]*?nanduclick\((.*?)\)[^>]*?>([^<]*?)<\/a>\B/", $response, $matchStr);
        $nanDuIndexList    = $matchStr[1];
        $nanDuValueList    = $matchStr[2];

        $nanDuMap = [];
        foreach($nanDuIndexList as $nanDuIndex => $nanDuValue){
            $nanDuMap[trim($nanDuValue, "'")] = $nanDuValueList[$nanDuIndex];
        }
        return $nanDuMap;
    }

    public static function getNodeList($response){
        $matchStr   = [];
        preg_match_all("/\bvar zNodes = (\[[^\]]*?\])\B/", $response, $matchStr);
        $nodeList   = $matchStr[1][0];

        $nodeList   = preg_replace('/"\s*?([\S\s]*?)\s*?"/', '“\1”', $nodeList);
        $nodeList   = preg_replace("/'\s*?([\S\s]*?)\s*?'/", '"\1"', $nodeList);
        $nodeList   = str_replace(" id:", '"id":', $nodeList);
        $nodeList   = str_replace(",pId:", ',"pId":', $nodeList);
        $nodeList   = str_replace(",name:", ',"name":', $nodeList);
        $nodeList   = json_decode($nodeList, true);
        return $nodeList;
    }

    public static function getQuestInfo($response){
        $matchStr   = [];
        preg_match_all('/\B<div class="questInfo[^>]*?>([\s\S]*?)<p class="questInfoTitle[^>]*?>/', $response, $matchStr);
        $questionAndResult = $matchStr[1] ?? [];

        $questInfoList = [];
        foreach($questionAndResult as $item){
            $xhrQuestion    = [];
            preg_match_all('/\B<div class="questInfo xhr_questInfo">([\s\S]*?)<\/div>\B/', $item, $xhrQuestion);
            $question       = [];
            preg_match_all('/\B<a href="javascript:t_toggle\(([0-9]*?)\);">[\s\S]*?(<p>[\s\S]*?)<\/a>\B/', $item, $question);
            $questionKeyList    = $question[1];
            $questionValueList  = $question[2];
            $questionList       = [];
            foreach($questionKeyList as $index => $key){
                $questionList[] = [
                    'key'   => $key,
                    'value' => $questionValueList[$index],
                ];
            }
            $questionContent    = [
                'questionRemark'    => $xhrQuestion[1],
                'questionList'      => $questionList,
            ];

            $result             = [];
            preg_match_all('/\B<div class="result" id="result_([0-9]*?)">[\s\S]*?(<table>[\s\S]*?<\/table>)\B/', $item, $result);
            $resultKeyList      = $result[1];
            $resultValueList    = $result[2];
            $resultList         = [];

            foreach($resultKeyList as $index => $key){
                $resultList[] = [
                    'key'   => $key,
                    'value' => $resultValueList[$index],
                ];
            }
            $resultContent      = [
                'resultRemark'  => '',
                'resultList'    => $resultList,
            ];

            $questInfoList[]    = [
                'questionContent'   => $questionContent,
                'resultContent'     => $resultContent,
            ];
        }
        return $questInfoList;
    }

    public static function formatQuestionContent($questionContent){
        $remarkRes          = "";
        $questionRemark     = $questionContent['questionRemark'];
        foreach($questionRemark as $remarkItem){
            $remarkRes      .= self::formatWhiteTag($remarkItem);
        }
        $remarkRes          = trim($remarkRes);

        $questionListRes    = [];
        $questionList       = $questionContent['questionList'];
        foreach($questionList as $questionItem){
            $key    = $questionItem['key'];
            $value  = self::formatWhiteTag($questionItem['value']);
            $questionListRes[$key] = trim($value);
        }
        return [
            'questionRemark'    => $remarkRes,
            'questionList'      => $questionListRes,
        ];
    }

    public static function formatResultContent($resultContent){
        $remarkRes      = "";

        $resultListRes  =   [];
        $resultList     = $resultContent['resultList'];
        foreach($resultList as $result){
            $key                    = $result['key'];
            $value                  = $result['value'];
            $result                 = trim($value);
            $temp                   = str_replace('&nbsp;', "", $result);
            $resultItem             = self::formatResultItem($temp);
            $resultListRes[$key]    = $resultItem;
        }
        return [
            'resultRemark'  => $remarkRes,
            'resultList'    => $resultListRes,
        ];
    }

    public static function formatResultItem($item){
        $matchArr = [];
        preg_match_all('/<td[^>]*?>([\s\S]*?)<\/td>/', $item, $matchArr);
        $resultData = $matchArr[1];

        $resultRes = [];
        $lastKey = "";
        foreach($resultData as $index => $resultItem){
            if($index % 2 != 0){
                $resultRes[$lastKey] = trim(self::formatWhiteTag($resultItem));
            }
            switch($resultItem){
                case "【答案】":{$lastKey = "answer";break;}
                case "【解析】":{$lastKey = "analysis";break;}
                case "【知识点】":{$lastKey = "knowledge_point";break;}
                case "【题点】":{$lastKey = "question_point";break;}
                case "【难易度】":{$lastKey = "difficulty";break;}
                case "【日期】":{$lastKey = "date";break;}
            }
        }
        return $resultRes;
    }

    public static function formatWhiteTag($content){
        $question       = trim($content);
        $temp           = preg_replace('/[^\S]*?<p[^>]*?>[^\S]*/', "", $question);
        $temp           = preg_replace('/[^\S]*?<li[^>]*?>[^\S]*/', "", $temp);
        $temp           = trim($temp);
        $temp           = preg_replace('/[^\S]*?<\/li[^>]*?>[^\S]*/', "\n", $temp);
        $temp           = preg_replace('/[^\S]*?<\/p[^>]*?>[^\S]*/', "\n", $temp);
        $temp           = preg_replace('/<[\/]*?if[^>]*?>/', "", $temp);
        $temp           = str_replace("<ol style=\"clear: both\">", "", $temp);
        $temp           = str_replace("</ol>", "", $temp);
        return $temp;
    }

    public static function getQuestionMd5($question){
        $content = $question['questionRemark'] . $question['resultRemark'] . json_encode($question['questList']);
        return md5($content);
    }

    public static function formatText($text){
        $text = str_replace('<i>', '', $text);
        $text = str_replace('</i>', '', $text);
        $text = str_replace('<b>', '', $text);
        $text = str_replace('</b>', '', $text);
        return $text;
    }
}