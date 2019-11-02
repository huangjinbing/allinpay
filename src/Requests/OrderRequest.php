<?php

namespace Tonglian\Allinpay\Requests;


class OrderRequest
{
    private $bizOrderNo;
    private $collectBizOrderNo;
    private $payToBankCardInfo;
    private $payerId;
    private $recieverId;
    private $recieverList;
    private $goodsType;
    private $bizGoodsNo;
    private $tradeCode;
    private $amount;
    private $fee;
    private $validateType;
    private $splitRule;
    private $frontUrl;
    private $backUrl;
    private $orderExpireDatetime;
    private $payMethod;
    private $goodsName;
    private $goodsDesc;
    private $industryCode;
    private $industryName;
    private $source;
    private $summary;
    private $extendInfo;
    private $collectPayList;
    private $bizUserId;
    private $accountSetNo;
    private $spliRuleList;
    private $oriBizOrderNo;
    private $refundType;
    private $refundList;
    private $couponAmount;
    private $feeAmount;
    private $bankCardNo;
    private $bankCardPro;
    private $withdrawType;
    private $tradeNo;
    private $verificationCode;
    private $consumerIp;
    private $bizBatchNo;
    private $batchPayList;
    private $bizTransferNo;
    private $sourceAccountSetNo;
    private $targetBizUserId;
    private $targetAccountSetNo;

    /**
     * @return mixed
     */
    public function getBizOrderNo()
    {
        return $this->bizOrderNo;
    }

    /**
     * @param mixed $bizOrderNo
     */
    public function setBizOrderNo($bizOrderNo): void
    {
        $this->bizOrderNo = $bizOrderNo;
    }

    /**
     * @return mixed
     */
    public function getCollectBizOrderNo()
    {
        return $this->collectBizOrderNo;
    }

    /**
     * @param mixed $collectBizOrderNo
     */
    public function setCollectBizOrderNo($collectBizOrderNo): void
    {
        $this->collectBizOrderNo = $collectBizOrderNo;
    }

    /**
     * @return mixed
     */
    public function getPayToBankCardInfo()
    {
        return $this->payToBankCardInfo;
    }

    /**
     * @param mixed $payToBankCardInfo
     */
    public function setPayToBankCardInfo($payToBankCardInfo): void
    {
        $this->payToBankCardInfo = $payToBankCardInfo;
    }

    /**
     * @return mixed
     */
    public function getPayerId()
    {
        return $this->payerId;
    }

    /**
     * @param mixed $payerId
     */
    public function setPayerId($payerId): void
    {
        $this->payerId = $payerId;
    }

    /**
     * @return mixed
     */
    public function getRecieverId()
    {
        return $this->recieverId;
    }

    /**
     * @param mixed $recieverId
     */
    public function setRecieverId($recieverId): void
    {
        $this->recieverId = $recieverId;
    }

    /**
     * @return mixed
     */
    public function getRecieverList()
    {
        return $this->recieverList;
    }

    /**
     * @param mixed $recieverList
     */
    public function setRecieverList($recieverList): void
    {
        $this->recieverList = $recieverList;
    }

    /**
     * @return mixed
     */
    public function getGoodsType()
    {
        return $this->goodsType;
    }

    /**
     * @param mixed $goodsType
     */
    public function setGoodsType($goodsType): void
    {
        $this->goodsType = $goodsType;
    }

    /**
     * @return mixed
     */
    public function getBizGoodsNo()
    {
        return $this->bizGoodsNo;
    }

    /**
     * @param mixed $bizGoodsNo
     */
    public function setBizGoodsNo($bizGoodsNo): void
    {
        $this->bizGoodsNo = $bizGoodsNo;
    }

    /**
     * @return mixed
     */
    public function getTradeCode()
    {
        return $this->tradeCode;
    }

    /**
     * @param mixed $tradeCode
     */
    public function setTradeCode($tradeCode): void
    {
        $this->tradeCode = $tradeCode;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getFee()
    {
        return $this->fee;
    }

    /**
     * @param mixed $fee
     */
    public function setFee($fee): void
    {
        $this->fee = $fee;
    }

    /**
     * @return mixed
     */
    public function getValidateType()
    {
        return $this->validateType;
    }

    /**
     * @param mixed $validateType
     */
    public function setValidateType($validateType): void
    {
        $this->validateType = $validateType;
    }

    /**
     * @return mixed
     */
    public function getSplitRule()
    {
        return $this->splitRule;
    }

    /**
     * @param mixed $splitRule
     */
    public function setSplitRule($splitRule): void
    {
        $this->splitRule = $splitRule;
    }

    /**
     * @return mixed
     */
    public function getFrontUrl()
    {
        return $this->frontUrl;
    }

    /**
     * @param mixed $frontUrl
     */
    public function setFrontUrl($frontUrl): void
    {
        $this->frontUrl = $frontUrl;
    }

    /**
     * @return mixed
     */
    public function getBackUrl()
    {
        return $this->backUrl;
    }

    /**
     * @param mixed $backUrl
     */
    public function setBackUrl($backUrl): void
    {
        $this->backUrl = $backUrl;
    }

    /**
     * @return mixed
     */
    public function getOrderExpireDatetime()
    {
        return $this->orderExpireDatetime;
    }

    /**
     * @param mixed $orderExpireDatetime
     */
    public function setOrderExpireDatetime($orderExpireDatetime): void
    {
        $this->orderExpireDatetime = $orderExpireDatetime;
    }

    /**
     * @return mixed
     */
    public function getPayMethod()
    {
        return $this->payMethod;
    }

    /**
     * @param mixed $payMethod
     */
    public function setPayMethod($payMethod): void
    {
        $this->payMethod = $payMethod;
    }

    /**
     * @return mixed
     */
    public function getGoodsName()
    {
        return $this->goodsName;
    }

    /**
     * @param mixed $goodsName
     */
    public function setGoodsName($goodsName): void
    {
        $this->goodsName = $goodsName;
    }

    /**
     * @return mixed
     */
    public function getGoodsDesc()
    {
        return $this->goodsDesc;
    }

    /**
     * @param mixed $goodsDesc
     */
    public function setGoodsDesc($goodsDesc): void
    {
        $this->goodsDesc = $goodsDesc;
    }

    /**
     * @return mixed
     */
    public function getIndustryCode()
    {
        return $this->industryCode;
    }

    /**
     * @param mixed $industryCode
     */
    public function setIndustryCode($industryCode): void
    {
        $this->industryCode = $industryCode;
    }

    /**
     * @return mixed
     */
    public function getIndustryName()
    {
        return $this->industryName;
    }

    /**
     * @param mixed $industryName
     */
    public function setIndustryName($industryName): void
    {
        $this->industryName = $industryName;
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param mixed $source
     */
    public function setSource($source): void
    {
        $this->source = $source;
    }

    /**
     * @return mixed
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @param mixed $summary
     */
    public function setSummary($summary): void
    {
        $this->summary = $summary;
    }

    /**
     * @return mixed
     */
    public function getExtendInfo()
    {
        return $this->extendInfo;
    }

    /**
     * @param mixed $extendInfo
     */
    public function setExtendInfo($extendInfo): void
    {
        $this->extendInfo = $extendInfo;
    }

    /**
     * @return mixed
     */
    public function getCollectPayList()
    {
        return $this->collectPayList;
    }

    /**
     * @param mixed $collectPayList
     */
    public function setCollectPayList($collectPayList): void
    {
        $this->collectPayList = $collectPayList;
    }

    /**
     * @return mixed
     */
    public function getBizUserId()
    {
        return $this->bizUserId;
    }

    /**
     * @param mixed $bizUserId
     */
    public function setBizUserId($bizUserId): void
    {
        $this->bizUserId = $bizUserId;
    }

    /**
     * @return mixed
     */
    public function getAccountSetNo()
    {
        return $this->accountSetNo;
    }

    /**
     * @param mixed $accountSetNo
     */
    public function setAccountSetNo($accountSetNo): void
    {
        $this->accountSetNo = $accountSetNo;
    }

    /**
     * @return mixed
     */
    public function getSplitRuleList()
    {
        return $this->spliRuleList;
    }

    /**
     * @param mixed $spliRuleList
     */
    public function setSpliRuleList($spliRuleList): void
    {
        $this->spliRuleList = $spliRuleList;
    }

    /**
     * @return mixed
     */
    public function getOriBizOrderNo()
    {
        return $this->oriBizOrderNo;
    }

    /**
     * @param mixed $oriBizOrderNo
     */
    public function setOriBizOrderNo($oriBizOrderNo): void
    {
        $this->oriBizOrderNo = $oriBizOrderNo;
    }

    /**
     * @return mixed
     */
    public function getRefundType()
    {
        return $this->refundType;
    }

    /**
     * @param mixed $refundType
     */
    public function setRefundType($refundType): void
    {
        $this->refundType = $refundType;
    }

    /**
     * @return mixed
     */
    public function getRefundList()
    {
        return $this->refundList;
    }

    /**
     * @param mixed $refundList
     */
    public function setRefundList($refundList): void
    {
        $this->refundList = $refundList;
    }

    /**
     * @return mixed
     */
    public function getCouponAmount()
    {
        return $this->couponAmount;
    }

    /**
     * @param mixed $couponAmount
     */
    public function setCouponAmount($couponAmount): void
    {
        $this->couponAmount = $couponAmount;
    }

    /**
     * @return mixed
     */
    public function getFeeAmount()
    {
        return $this->feeAmount;
    }

    /**
     * @param mixed $feeAmount
     */
    public function setFeeAmount($feeAmount): void
    {
        $this->feeAmount = $feeAmount;
    }

    /**
     * @return mixed
     */
    public function getBankCardNo()
    {
        return $this->bankCardNo;
    }

    /**
     * @param mixed $bankCardNo
     */
    public function setBankCardNo($bankCardNo): void
    {
        $this->bankCardNo = $bankCardNo;
    }

    /**
     * @return mixed
     */
    public function getBankCardPro()
    {
        return $this->bankCardPro;
    }

    /**
     * @param mixed $bankCardPro
     */
    public function setBankCardPro($bankCardPro): void
    {
        $this->bankCardPro = $bankCardPro;
    }

    /**
     * @return mixed
     */
    public function getWithdrawType()
    {
        return $this->withdrawType;
    }

    /**
     * @param mixed $withdrawType
     */
    public function setWithdrawType($withdrawType): void
    {
        $this->withdrawType = $withdrawType;
    }

    /**
     * @return mixed
     */
    public function getTradeNo()
    {
        return $this->tradeNo;
    }

    /**
     * @param mixed $tradeNo
     */
    public function setTradeNo($tradeNo): void
    {
        $this->tradeNo = $tradeNo;
    }

    /**
     * @return mixed
     */
    public function getVerificationCode()
    {
        return $this->verificationCode;
    }

    /**
     * @param mixed $verificationCode
     */
    public function setVerificationCode($verificationCode): void
    {
        $this->verificationCode = $verificationCode;
    }

    /**
     * @return mixed
     */
    public function getConsumerIp()
    {
        return $this->consumerIp;
    }

    /**
     * @param mixed $consumerIp
     */
    public function setConsumerIp($consumerIp): void
    {
        $this->consumerIp = $consumerIp;
    }

    /**
     * @return mixed
     */
    public function getBizBatchNo()
    {
        return $this->bizBatchNo;
    }

    /**
     * @param mixed $bizBatchNo
     */
    public function setBizBatchNo($bizBatchNo): void
    {
        $this->bizBatchNo = $bizBatchNo;
    }

    /**
     * @return mixed
     */
    public function getBatchPayList()
    {
        return $this->batchPayList;
    }

    /**
     * @param mixed $batchPayList
     */
    public function setBatchPayList($batchPayList): void
    {
        $this->batchPayList = $batchPayList;
    }

    /**
     * @return mixed
     */
    public function getBizTransferNo()
    {
        return $this->bizTransferNo;
    }

    /**
     * @param mixed $bizTransferNo
     */
    public function setBizTransferNo($bizTransferNo): void
    {
        $this->bizTransferNo = $bizTransferNo;
    }

    /**
     * @return mixed
     */
    public function getSourceAccountSetNo()
    {
        return $this->sourceAccountSetNo;
    }

    /**
     * @param mixed $sourceAccountSetNo
     */
    public function setSourceAccountSetNo($sourceAccountSetNo): void
    {
        $this->sourceAccountSetNo = $sourceAccountSetNo;
    }

    /**
     * @return mixed
     */
    public function getTargetBizUserId()
    {
        return $this->targetBizUserId;
    }

    /**
     * @param mixed $targetBizUserId
     */
    public function setTargetBizUserId($targetBizUserId): void
    {
        $this->targetBizUserId = $targetBizUserId;
    }

    /**
     * @return mixed
     */
    public function getTargetAccountSetNo()
    {
        return $this->targetAccountSetNo;
    }

    /**
     * @param mixed $targetAccountSetNo
     */
    public function setTargetAccountSetNo($targetAccountSetNo): void
    {
        $this->targetAccountSetNo = $targetAccountSetNo;
    }
}