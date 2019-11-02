<?php
/**
 *
 *
 * @date 2019/9/5 15:34
 */

namespace TongLian\Allinpay\Port;

use TongLian\Allinpay\Requests\MemberRequest;

class MemberService
{
    /**
     * 创建会员 4.1.1
     *
     * @param MemberRequest $request
     * @return array
     */
    public function createMember(MemberRequest $request)
    {
        $param = [
            'bizUserId' => $request->getBizUserId(),
            'memberType' => $request->getMemberType(),
            'source' => $request->getSource(),
            'extendParam' => $request->getExtendParam(),
        ];

        return app('allinpay')->AllinpayCurl('MemberService', 'createMember', $param);
    }

    /**
     * 查询会员 4.1.8
     *
     * @param MemberRequest $request
     * @return array
     */
    public function getMemberInfo(MemberRequest $request)
    {
        $param = [
            'bizUserId' => $request->getBizUserId()
        ];

        return app('allinpay')->AllinpayCurl('MemberService', 'getMemberInfo', $param);
    }

    /**
     * 发送短信验证码 4.1.2
     *
     * @param MemberRequest $request
     * @return array|mixed
     */
    public function sendVerificationCode(MemberRequest $request)
    {
        $param = [
            'bizUserId' => $request->getBizUserId(),
            'phone' => $request->getPhone(),
            'verificationCodeType' => $request->getVerificationCodeType(),
        ];
        return app('allinpay')->AllinpayCurl('MemberService', 'sendVerificationCode', $param);
    }

    /**
     * 绑定手机 4.1.3
     *
     * @param MemberRequest $request
     * @return array
     */
    public function bindPhone(MemberRequest $request)
    {
        $param = [
            'bizUserId' => $request->getBizUserId(),
            'phone' => $request->getPhone(),
            'verificationCode' => $request->getVerificationCode(),
        ];
        return app('allinpay')->AllinpayCurl('MemberService', 'bindPhone', $param);
    }


    /**
     * 会员绑定支付账户用户标识 4.1.23
     *
     * @param MemberRequest $request
     * @return array|mixed
     */
    public function applyBindAcct(MemberRequest $request)
    {
        $param = [
            'bizUserId' => $request->getBizUserId(),
            'operationType' => $request->getOperationType(),
            // weChatPublic -微信公众号 weChatMiniProgram -微信小程序 aliPayService -支付宝生活号
            'acctType' => $request->getAcctType(),
            // 微信公众号支付 openid——微信分配 微信小程序支付 openid——微信分配 支付宝生活号支付 user_id——支付宝分配 附：openid 示例 oUpF8uMuAJO_M2pxb1Q9zNjWeS6o
            'acct' => $request->getAcct(),
        ];
        return app('allinpay')->AllinpayCurl('MemberService', 'applyBindAcct', $param);
    }

    /**
     * 会员电子协议签约 4.1.4
     * @param MemberRequest $request
     * @return array|mixed
     */
    public function signContract(MemberRequest $request)
    {
        $param = [
            'bizUserId' => $request->getBizUserId(),
            'jumpUrl' => $request->getJumpUrl(),
            'backUrl' => $request->getBackUrl(),
            'source' => $request->getSource(),
        ];
        return app('allinpay')->getPaymentCodeParams('MemberService', 'signContract', $param);
    }

    /**
     * 个人实名认证 4.1.5
     *
     * @param MemberRequest $request
     * @return array|mixed
     */
    public function setRealName(MemberRequest $request)
    {
        $param = [
            'bizUserId' => $request->getBizUserId(),
            'name' => $request->getName(),
            // 类型（身份证=1）目前只支持身份证
            'identityType' => $request->getIdentityType(),
            // RSA 加密
            'identityNo' => app('allinpay')->RsaEncode($request->getIdentityNo()),
            'isAuth' => $request->getIsAuth(),
        ];

        return app('allinpay')->AllinpayCurl('MemberService', 'setRealName', $param);
    }

    /**
     * 设置企业信息 4.1.6
     *
     * @param MemberRequest $request
     * @return array|mixed
     */
    public function setCompanyInfo(MemberRequest $request)
    {
        $param = [
            'bizUserId' => $request->getBizUserId(),
            'backUrl' => $request->getBackUrl() ?? '',
            // JSONObject
            'companyBasicInfo' => $request->getCompanyBasicInfo(),
            'isAuth' => $request->getIsAuth(),
            'companyExtendInfo' => $request->getCompanyExtendInfo(),
        ];

        return app('allinpay')->AllinpayCurl('MemberService', 'setCompanyInfo', $param);
    }

    /**
     * 请求绑定银行卡 4.1.10
     *
     * @param MemberRequest $request
     * @return array|mixed
     */
    public function applyBindBankCard(MemberRequest $request)
    {
        $param = [
            'bizUserId' => $request->getBizUserId(),
            'cardNo' => app('allinpay')->RsaEncode($request->getCardNo()),
            'phone' => $request->getPhone(),
            'name' => $request->getName(),
            // 类型（身份证=1）目前只支持身份证
            'identityType' => $request->getIdentityType(),
            'identityNo' => app('allinpay')->RsaEncode($request->getIdentityNo()),

            // 以下为非必填
            'cardCheck' => $request->getCardCheck(),
            'validate' => $request->getValidate(),
            'cvv2' => $request->getCvv2() ? app('allinpay')->RsaEncode($request->getCvv2()) : null,
            'isSafeCard' => $request->getIsSafeCard(),
            'unionBank' => $request->getUnionBank(),
        ];

        return app('allinpay')->AllinpayCurl('MemberService', 'applyBindBankCard', $param);
    }

    /**
     * 确认绑定银行卡 4.1.11
     * @param MemberRequest $request
     * @return array|mixed
     */
    public function bindBankCard(MemberRequest $request)
    {
        $param = [
            'bizUserId' => $request->getBizUserId(),
            'tranceNum' => $request->getTranceNum(),
            'transDate' => $request->getTransDate(),
            'phone' => $request->getPhone(),
            'validate' => $request->getvalidate() ? app('allinpay')->RsaEncode($request->getvalidate()) : null,
            'cvv2' => $request->getCvv2() ? app('allinpay')->RsaEncode($request->getCvv2()) : null,
            'verificationCode' => $request->getVerificationCode(),
        ];
        return app('allinpay')->AllinpayCurl('MemberService', 'bindBankCard', $param);
    }

    /**
     * 确认绑定银行卡 4.1.14
     * @param MemberRequest $request
     * @return array|mixed
     */
    public function unbindBankCard(MemberRequest $request)
    {
        $param = [
            'bizUserId' => $request->getBizUserId(),
            'cardNo' => $request->getCardNo() ? app('allinpay')->RsaEncode($request->getCardNo()) : null,
        ];
        return app('allinpay')->AllinpayCurl('MemberService', 'unbindBankCard', $param);
    }

    /**
     * 查询绑定银行卡 4.1.13
     * @param MemberRequest $request
     * @return array|mixed
     */
    public function queryBankCard(MemberRequest $request)
    {
        $param = [
            'bizUserId' => $request->getBizUserId(),
            'cardNo' => $request->getCardNo() ? app('allinpay')->RsaEncode($request->getCardNo()) : '',
        ];
        return app('allinpay')->AllinpayCurl('MemberService', 'queryBankCard', $param);
    }

    /**
     * 查询卡 bin 4.1.9
     *
     * @param MemberRequest $request
     * @return array|mixed
     */
    public function getBankCardBin(MemberRequest $request)
    {
        $param = [
            'cardNo' => $request->getCardNo() ? app('allinpay')->RsaEncode($request->getCardNo()) : '',
        ];
        return app('allinpay')->AllinpayCurl('MemberService', 'getBankCardBin', $param);
    }

    /**
     * 4.1.17 设置支付密码【密码验证版】
     *
     * @param MemberRequest $request
     * @return array|mixed
     */
    public function setPayPwd(MemberRequest $request)
    {
        $param = [
            'bizUserId' => $request->getBizUserId(),
            'phone' => $request->getPhone(),
            'name' => $request->getName(),
            'identityType' => $request->getIdentityType(),
            'identityNo' => $request->getIdentityNo() ? app('allinpay')->RsaEncode($request->getIdentityNo()) : null,
            'jumpUrl' => $request->getJumpUrl(),
            'backUrl' => $request->getBackUrl()
        ];
        return app('allinpay')->getPaymentCodeParams('MemberPwdService', 'setPayPwd', $param);
    }

    /**
     * 4.1.17 修改支付密码【密码验证版】
     *
     * @param MemberRequest $request
     * @return array|mixed
     */
    public function updatePayPwd(MemberRequest $request)
    {
        $param = [
            'bizUserId' => $request->getBizUserId(),
            'phone' => $request->getPhone(),
            'name' => $request->getName(),
            'identityType' => $request->getIdentityType(),
            'identityNo' => $request->getIdentityNo() ? app('allinpay')->RsaEncode($request->getIdentityNo()) : null,
            'jumpUrl' => $request->getJumpUrl(),
            'backUrl' => $request->getBackUrl()
        ];
        return app('allinpay')->getPaymentCodeParams('MemberPwdService', 'updatePayPwd', $param);
    }

    /**
     * 4.1.17 重置支付密码【密码验证版】
     *
     * @param MemberRequest $request
     * @return array|mixed
     */
    public function resetPayPwd(MemberRequest $request)
    {
        $param = [
            'bizUserId' => $request->getBizUserId(),
            'phone' => $request->getPhone(),
            'name' => $request->getName(),
            'identityType' => $request->getIdentityType(),
            'identityNo' => $request->getIdentityNo() ? app('allinpay')->RsaEncode($request->getIdentityNo()) : null,
            'jumpUrl' => $request->getJumpUrl(),
            'backUrl' => $request->getBackUrl()
        ];
        return app('allinpay')->AllinpayCurl('MemberPwdService', 'resetPayPwd', $param);
    }
}