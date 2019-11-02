<?php

namespace TongLian\Allinpay\Helper;

class Tools
{
    /**
     * 写入成功返回
     *
     * @param string $message
     * @param int $code
     * @param array $data
     * @return array
     */
    public static function success($message = '写入成功', $code = 0, $data = [])
    {
        if (defined('ERROR_CODE')) $code = ERROR_CODE;
        $response = [
            'status' => 'success',
            'status_code' => 200,
            'error' => 0,
            'code' => $code,
            'message' => $message
        ];
        if (!empty($data)) $response = array_merge($response, ['data' => $data]);
        return $response;
    }

    /**
     * 写入成功返回
     *
     * @param array $data
     * @param int $code
     * @return array
     */
    public static function setData($data = [], $code = 0)
    {
        if (defined('ERROR_CODE')) $code = ERROR_CODE;
        $response = [
            'status' => 'success',
            'status_code' => 200,
            'error' => 0,
            'code' => $code,
            'data' => $data
        ];
        return $response;
    }

    /**
     * 写入失败返回
     *
     * @param string $message
     * @param int $code
     * @param array $data
     * @return array
     */
    public static function error($message = '写入失败', $code = 0, $data = [])
    {
        if (defined('ERROR_CODE')) $code = ERROR_CODE;
        $response = [
            'status' => 'failed',
            'status_code' => 500,
            'error' => 1,
            'code' => $code,
            'message' => $message
        ];
        if (!empty($data)) $response = array_merge($response, ['data' => $data]);
        return $response;
    }

}