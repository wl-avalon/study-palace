<?php

namespace ploanframework\actions\product;

use ploanframework\actions\BaseAction;

/**
 * 借款说明
 * Class GetAction
 * @package app\modules\ploanframework\actions\product
 */
class GetAction extends BaseAction{
    /**
     * @return array
     */
    public function getRules(): array{
        return [
            'memberID'  => 'required',
            'productID' => 'required',
            'type'      => 'required',
        ];
    }

    /**
     * @return array
     */
    public function getFormat(): array{
        return [
            'memberInfo'  => [
                'memberID'   => ['source' => 'memberInfo.user_id', 'comment' => '用户ID'],
                'memberName' => ['source' => 'memberInfo.user_name', 'comment' => '用户名'],
            ],
            'productList' => [
                'source'  => 'list',
                'comment' => '产品列表',
                'types'   => [
                    'productID'   => ['source' => 'productUUID', 'comment' => '产品ID'],
                    'amount'      => ['source' => 'available', 'types' => 'Money:decimal', 'comment' => '当前可借金额'],
                    'productType' => ['source' => 'productType', 'types' => 'Enum:productType', 'comment' => '产品类型'],
                ],
            ],
        ];
    }

    public function execute(){
        return [
            'memberInfo' => [
                'user_id'   => 'asdasdasd',
                'user_name' => '王定君',
            ],
            'list'       => [
                [
                    'productUUID' => '234345345345',
                    'available'   => 23.232346,
                    'productType' => 1,
                ],
                [
                    'productUUID' => '234345345345',
                    'available'   => 23.232346,
                    'productType' => 1,
                ],
                [
                    'productUUID' => '234345345345',
                    'available'   => 23.232346,
                    'productType' => 1,
                ],
                [
                    'productUUID' => '234345345345',
                    'available'   => 23.232346,
                    'productType' => 1,
                ],
            ],
        ];
    }
}