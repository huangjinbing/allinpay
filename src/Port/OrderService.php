<?php
/**
 *
 *
 * @date 2019/9/5 15:34
 */

namespace TongLian\Allinpay\Port;

use TongLian\Allinpay\Requests\OrderRequest;

class OrderService
{
    /**
     * 托管代收申请-简化版
     *
     * @param OrderRequest $request
     * @return array|mixed
     */
    public function agentCollectApplySimplifyCheck(OrderRequest $request)
    {
        $param = [
            'bizOrderNo' => $request->getBizOrderNo(),
            'payerId' => $request->getPayerId(),
            'goodsType' => $request->getGoodsType(),
            'bizGoodsNo' => $request->getBizGoodsNo(),
            'tradeCode' => $request->getTradeCode(),
            'amount' => $request->getAmount(),
            'fee' => $request->getFee(),
            'validateType' => $request->getValidateType(),
            'frontUrl' => $request->getFrontUrl(),
            'backUrl' => $request->getBackUrl(),
            'orderExpireDatetime' => $request->getOrderExpireDatetime(),
            'payMethod' => $request->getPayMethod(),
            'goodsName' => $request->getGoodsName(),
            'goodsDesc' => $request->getGoodsDesc(),
            'industryCode' => $request->getIndustryCode(),
            'industryName' => $request->getIndustryName(),
            'source' => $request->getSource(),
            'summary' => $request->getSummary(),
            'extendInfo' => $request->getExtendInfo(),
        ];

        return app('allinpay')->AllinpayCurl('OrderService', 'agentCollectApplySimplifyCheck', $param);
    }

    /**
     * 托管代收申请-标准版
     *
     * @param OrderRequest $request
     * @return array
     */
    public function agentCollectApply(OrderRequest $request)
    {
        $param = [
            'bizOrderNo' => $request->getBizOrderNo(),
            'payerId' => $request->getPayerId(),
            'recieverList' => $request->getRecieverList(),
            'goodsType' => $request->getGoodsType(),
            'bizGoodsNo' => $request->getBizGoodsNo(),
            'tradeCode' => $request->getTradeCode(),
            'amount' => $request->getAmount(),
            'fee' => $request->getFee(),
            'validateType' => $request->getValidateType(),
            'frontUrl' => $request->getFrontUrl(),
            'backUrl' => $request->getBackUrl(),
            'orderExpireDatetime' => $request->getOrderExpireDatetime(),
            'payMethod' => $request->getPayMethod(),
            'goodsName' => $request->getGoodsName(),
            'goodsDesc' => $request->getGoodsDesc(),
            'industryCode' => $request->getIndustryCode(),
            'industryName' => $request->getIndustryName(),
            'source' => $request->getSource(),
            'summary' => $request->getSummary(),
            'extendInfo' => $request->getExtendInfo(),
        ];

        return app('allinpay')->AllinpayCurl('OrderService', 'agentCollectApply', $param);
    }

    /**
     * 单笔代付-简化版
     * @param OrderRequest $request
     * @return array|mixed
     */
    public function signalAgentPaySimplifyCheck(OrderRequest $request)
    {
        $param = [
            'bizOrderNo' => $request->getBizOrderNo(),
            'collectbizOrderNo' => $request->getCollectBizOrderNo(),
            'bizUserId' => $request->getBizUserId(),
            'accountSetNo' => $request->getAccountSetNo(),
            'backUrl' => $request->getBackUrl(),
            'payToBankCardInfo' => $request->getPayToBankCardInfo(),
            'amount' => $request->getAmount(),
            'fee' => $request->getFee(),
            'splitRuleList' => $request->getSplitRuleList(),
            'goodsType' => $request->getGoodsType(),
            'bizGoodsNo' => $request->getBizGoodsNo(),
            'tradeCode' => $request->getTradeCode(),
            'summary' => $request->getSummary(),
            'extendInfo' => $request->getExtendInfo(),
        ];

        return app('allinpay')->AllinpayCurl('OrderService', 'signalAgentPaySimplifyCheck', $param);
    }

    /**
     * 托管代付申请
     *
     * @param OrderRequest $request
     * @return array
     */
    public function signalAgentPay(OrderRequest $request)
    {
        $param = [
            'bizOrderNo' => $request->getBizOrderNo(),
            'collectPayList' => $request->getCollectPayList(),
            'bizUserId' => $request->getBizUserId(),
            'accountSetNo' => $request->getAccountSetNo(),
            'backUrl' => $request->getBackUrl(),
            'amount' => $request->getAmount(),
            'fee' => $request->getFee(),
            'splitRuleList' => $request->getSplitRuleList(),
            'goodsType' => $request->getGoodsType(),
            'bizGoodsNo' => $request->getBizGoodsNo(),
            'tradeCode' => $request->getTradeCode(),
            'summary' => $request->getSummary(),
            'extendInfo' => $request->getExtendInfo(),
        ];
        return app('allinpay')->AllinpayCurl('OrderService', 'signalAgentPay', $param);
    }

    /**
     * 消费申请
     *
     * @param OrderRequest $request
     * @return array|mixed
     */
    public function consumeApply(OrderRequest $request)
    {
        $param = [
            'payerId' => $request->getPayerId(),
            'recieverId' => $request->getRecieverId(),
            'bizOrderNo' => $request->getBizOrderNo(),
            'amount' => $request->getAmount(),
            'fee' => $request->getFee(),
            'validateType' => $request->getValidateType(),
            'splitRule' => $request->getSplitRule(),
            'frontUrl' => $request->getFrontUrl(),
            'backUrl' => $request->getBackUrl(),
            'orderExpireDatetime' => $request->getOrderExpireDatetime(),
            'payMethod' => $request->getPayMethod(),
            'goodsType' => $request->getGoodsType(),
            'bizGoodsNo' => $request->getBizGoodsNo(),
            'goodsName' => $request->getGoodsName(),
            'goodsDesc' => $request->getGoodsDesc(),
            'industryCode' => $request->getIndustryCode(),
            'industryName' => $request->getIndustryName(),
            'source' => $request->getSource(),
            'summary' => $request->getSummary(),
            'extendInfo' => $request->getExtendInfo(),
        ];
        return app('allinpay')->AllinpayCurl('OrderService', 'consumeApply', $param);
    }

    /**
     * 4.2.16 退款申请
     *
     * @param OrderRequest $request
     * @return array|mixed
     */
    public function refund(OrderRequest $request)
    {
        $param = [
            'bizOrderNo' => $request->getBizOrderNo(),
            'oriBizOrderNo' => $request->getOriBizOrderNo(),
            'bizUserId' => $request->getBizUserId(),
            'refundType' => $request->getRefundType(),
            'refundList' => $request->getRefundList(),
            'backUrl' => $request->getBackUrl(),
            'amount' => $request->getAmount(),
            'couponAmount' => $request->getCouponAmount(),
            'feeAmount' => $request->getFeeAmount(),
            'extendInfo' => $request->getExtendInfo(),
        ];
        return app('allinpay')->AllinpayCurl('OrderService', 'refund', $param);
    }

    /**
     * 提现申请
     *
     * @param OrderRequest $request
     * @return array|mixed
     */
    public function withdrawApply(OrderRequest $request)
    {
        $param = [
            'bizOrderNo' => $request->getBizOrderNo(),
            'bizUserId' => $request->getBizUserId(),
            'accountSetNo' => $request->getAccountSetNo(),
            'amount' => $request->getAmount(),
            'fee' => $request->getFee(),
            'validateType' => $request->getValidateType(),
            'backUrl' => $request->getBackUrl(),
            'orderExpireDatetime' => $request->getOrderExpireDatetime(),
            'payMethod' => $request->getPayMethod(),
            'bankCardNo' => $request->getBankCardNo() ? app('allinpay')->RsaEncode($request->getBankCardNo()) : '',
            'bankCardPro' => $request->getBankCardPro(),
            'withdrawType' => $request->getWithdrawType(),
            'industryCode' => $request->getIndustryCode(),
            'industryName' => $request->getIndustryName(),
            'source' => $request->getSource(),
            'summary' => $request->getSummary(),
            'extendInfo' => $request->getExtendInfo(),
        ];
        return app('allinpay')->AllinpayCurl('OrderService', 'withdrawApply', $param);
    }

    /**
     * 确认支付(后台+短信验证码确认)
     *
     * @param OrderRequest $request
     * @return array|mixed
     */
    public function pay(OrderRequest $request)
    {
        $param = [
            'bizUserId' => $request->getBizUserId(),
            'bizOrderNo' => $request->getBizOrderNo(),
            'tradeNo' => $request->getTradeNo(),
            'verificationCode' => $request->getVerificationCode(),
            'consumerIp' => $request->getConsumerIp(),
        ];
        return app('allinpay')->AllinpayCurl('OrderService', 'pay', $param);
    }

    /**
     * 查询余额
     *
     * @param OrderRequest $request
     * @return array|mixed
     */
    public function queryBalance(OrderRequest $request)
    {
        $param = [
            'bizUserId' => $request->getBizUserId(),
            'accountSetNo' => $request->getAccountSetNo(),
        ];
        return app('allinpay')->AllinpayCurl('OrderService', 'queryBalance', $param);
    }

    /**
     * 4.2.7 批量托管代付（标准版）
     *
     * @param OrderRequest $request
     * @return array|mixed
     */
    public function batchAgentPay(OrderRequest $request)
    {
        $param = [
            'bizBatchNo' => $request->getBizBatchNo(),
            'batchPayList' => $request->getBatchPayList(),
            'goodsType' => $request->getgoodsType(),
            'bizGoodsNo' => $request->getBizGoodsNo(),
            'tradeCode' => $request->gettradeCode(),
        ];
        return app('allinpay')->AllinpayCurl('OrderService', 'batchAgentPay', $param);
    }

    /**
     * 4.2.17 平台转账
     *
     * @param OrderRequest $request
     * @return array|mixed
     */
    public function applicationTransfer(OrderRequest $request)
    {
        $param = [
            'bizTransferNo' => $request->getBizTransferNo(),
            'sourceAccountSetNo' => $request->getSourceAccountSetNo(),
            'targetBizUserId' => $request->getTargetBizUserId(),
            'targetAccountSetNo' => $request->getTargetAccountSetNo(),
            'amount' => $request->getAmount(),
            'extendInfo' => $request->getExtendInfo(),
        ];
        return app('allinpay')->AllinpayCurl('OrderService', 'applicationTransfer', $param);
    }

    /**
     * 4.2.19 查询订单状态
     *
     * @param OrderRequest $request
     * @return array|mixed
     */
    public function getOrderDetail(OrderRequest $request)
    {
        $param = [
            'bizOrderNo' => $request->getBizOrderNo()
        ];
        return app('allinpay')->AllinpayCurl('OrderService', 'getOrderDetail', $param);
    }

}