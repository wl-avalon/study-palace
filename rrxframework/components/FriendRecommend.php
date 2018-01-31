<?php
namespace rrxframework\components;
use rrxframework\service\Datagent;
use rrxframework\service\Friend;
use rrxframework\service\Passport;
use Yii;

class FriendRecommend {

    const GET_RECOMMEND_LIST = '/datagent/inner/friend/get-list';

    /**
     * 获取好友推荐列表
     * 依赖datagent passport friend系统,使用前请先按照标准配置datagent passport friend的应用配置信息
     *
     * @param string $memberId 用户ID
     * @param int $size 需要获取的记录数量
     * @param float $minScore 推荐好友的最小的分数
     * @param int $minSize 需要获取的最少记录数,如果查询到的记录总数不足这个值,直接返回为空
     * @return array
     */
    static public function getList($memberId, $size, $minScore, $minSize) {
        $recommendList = [];

        // 推荐数据获取
        $datagentInput = [
            'memberID' => $memberId,
            'type'    => 0,
            'size'    => $size,
            'score'   => $minScore,
        ];

        $status = Datagent::advancedCall(self::GET_RECOMMEND_LIST, $datagentInput, $error, $datagentData);

        if ($status == false) {
            return $recommendList;
        }

        if (empty($datagentData['userlist']) || !is_array($datagentData['userlist'])
            || (count($datagentData['userlist']) < $minSize)) {
            return $recommendList;
        }

        $userIdList = $datagentData['userlist'];

        // 用户信息获取
        $passportInput = [
            'user_id_list' => implode(',', $userIdList),
            'result_type' => 2,
            'fields' => 'base,ext',
        ];

        $status = Passport::getUserList($passportInput, $error, $passportData);

        if ($status == false) {
            return $recommendList;
        }

        $userIdToInfoMap = $passportData;

        if (count($userIdToInfoMap) < $minSize) {
            return $recommendList;
        }

        // 关系获取
        $userIdList = array_keys($userIdToInfoMap);
        $friendInput = [
            'memberID' => $memberId,
            'friendIDList' => implode(',', $userIdList),
            'fields' => 'company_info,common_friend_num',
            'isShowNoRelation' => 1,
        ];

        $status = Friend::getFollowRelationList($friendInput, $error, $friendData);

        if ($status == false) {
            return $recommendList;
        }

        if (empty($friendData['followList']) || !is_array($friendData['followList'])) {
            return $recommendList;
        }

        $userIdToFollowRelationInfoMap = $friendData['followList'];

        // 数据组合
        // 有头像用户详细信息列表
        $userWithAvatarDetailList = [];
        // 无头像用户详细信息列表
        $userWithoutAvatarDetailList = [];
        foreach ($userIdToFollowRelationInfoMap as $userId => $followRelationInfo) {
            $userInfo = $userIdToInfoMap[$userId];

            $content = self::formatRecommendContent($followRelationInfo);

            $item = [
                'memberID' => $userInfo['user_id'],
                'memberName' => $userInfo['user_name'],
                'avatarUrl' => $userInfo['avatar_url'],
                'thumbnailUrl' => $userInfo['thumbnail_url'],
                'remarkName' => null,
                'sex' => $userInfo['ext']['sex'],
                'level' => 1,
                'content' => $content,
                'source' => 7,
            ];

            if (!empty($item['avatarUrl'])) {
                $userWithAvatarDetailList[] = $item;
            } else {
                $userWithoutAvatarDetailList[] = $item;
            }
        }

        //每次对有头像和没有头像的分组乱序后合并展示
        if (!empty($userWithAvatarDetailList)) {
            shuffle($userWithAvatarDetailList);
        }
        if (!empty($userWithoutAvatarDetailList)) {
            shuffle($userWithoutAvatarDetailList);
        }

        $recommendList = array_merge($userWithAvatarDetailList, $userWithoutAvatarDetailList);

        return $recommendList;
    }

    static protected function formatRecommendContent($followRelationInfo) {
        $content = '';

        if (isset($followRelationInfo['commonFriend']['count'])
            && $followRelationInfo['commonFriend']['count'] > 0) {
            $content = sprintf('%d位共同好友', $followRelationInfo['commonFriend']['count']);
        }

        if (!empty($content)) {
            if (!empty($followRelationInfo['companyList'])) {
                $content = '同事、' . $content;
            }
        } else {
            $content = '您的二度人脉';
        }

        return $content;
    }
}