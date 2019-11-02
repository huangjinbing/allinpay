<?php
/**
 *
 *
 * @date 2019/9/5 15:34
 */

namespace Tonglian\Allinpay\Port;

use Tonglian\Allinpay\Requests\MerchantRequest;

class MerchantService
{
    /**
     * 平台集合对账下载接口
     *
     * @param MerchantRequest $request
     * @return array
     */
    public function getCheckAccountFile(MerchantRequest $request)
    {
        $param = [
            'date'    =>  $request->getDate(),
            'fileType'    =>  $request->getFileType(),
        ];

        return app('allinpay')->AllinpayCurl('MerchantService', 'getCheckAccountFile', $param);
    }

    /**
     * 通联通头寸查询
     *
     * @param MerchantRequest $request
     * @return array
     */
    public function queryReserveFundBalance(MerchantRequest $request)
    {
        $param = [];
        return app('allinpay')->AllinpayCurl('MerchantService', 'queryReserveFundBalance', $param);
    }

}