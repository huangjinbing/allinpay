<?php

namespace App\Helpers;

use App\Exceptions\RequestException;
use App\Exceptions\ValidationException;
use App\Models\AfterSaleOrder;
use App\Models\CustomerOrder;
use App\Models\GoodsSku;
use App\Models\PayTrade;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Tools
{
    // 加密串
    const ENCRYPT_KEY = 'rgB5N8B+DOSoZA10jmcAR6Eg3pPYj950';

    /**
     * 写入成功返回
     *
     * @param string $message
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

    /**
     * 设置日志文件名
     *
     * @author huangjinbing
     * @date   2019-01-17
     * @param $fileName
     * @param $bugLevel
     * @since  PM_1.3_sibu
     * @return Logger
     * @throws \Exception
     */
    public static function setFileName($fileName, $bugLevel)
    {
        if (!env('DEFINING_LOG_FILE_ON', true)) $fileName = 'lumen';
        $stream = new StreamHandler(storage_path('logs/' . $fileName . '.log'), $bugLevel);
        $stream->setFormatter(new LineFormatter(null, null, true, true));
        $log = new Logger($fileName);
        $log->pushHandler($stream);

        return $log;
    }

    /**
     * 单个日志输出
     *
     * @param $content
     * @throws \Exception
     */
    public static function logInfo($content, $title = null, $fileName = 'lumen')
    {
        if (env('LOG_ON', true)) {
            $log = self::setFileName($fileName, Logger::INFO);
            if ($title) $log->info($title);
            $log->info('==========================');
            $log->info(print_r($content, true));
            $log->info('==========================');
        }
    }

    /**
     * 单个错误日志输出
     *
     * @param string $content
     * @throws \Exception
     */
    public static function logError($content, $title = null, $fileName = 'lumen')
    {
        $log = self::setFileName($fileName, Logger::ERROR);
        if ($title) $log->info($title);
        $log->error('**************************');
        $log->error($content);
        $log->error('**************************');
    }

    /**
     * 事务异常错误日志输出
     *
     * @param $exception
     * @throws \Exception
     */
    public static function logUnusualError($exception, $title = null, $fileName = 'lumen')
    {
        $log = self::setFileName($fileName, Logger::ERROR);
        if ($title) $log->info($title);
        $log->error('**************************');
        $log->error("\n"
            . "----------------------------------------\n"
            . "| 错误信息 | {$exception->getMessage()}\n"
            . "| 文件路径 | {$exception->getFile()} (第{$exception->getLine()}行)\n"
            . "| 访问路径 | [" . request()->method() . "] " . request()->url() . "\n"
            . "| 请求参数 | " . json_encode(request()->all()) . "\n"
            . "----------------------------------------\n");
        $log->error('**************************');
    }

    /**
     * 多个日志一次性输出
     *
     * @param      $content
     * @param null $subject
     * @return bool
     * @throws \Exception
     */
    public static function singleLog($content, $title = null, $isEnd = false, $fileName = 'lumen')
    {
        if (!isset($GLOBALS['debugArray'])) {
            $GLOBALS['debugArray'] = array();
        }

        if ($title) {
            array_push($GLOBALS['debugArray'], $title);
            array_push($GLOBALS['debugArray'], '==========================');
        }

        if ($content) {
            array_push($GLOBALS['debugArray'], print_r($content, true));
            array_push($GLOBALS['debugArray'], '--------------------------');
        }

        if ($isEnd) {
            self::logInfo($GLOBALS['debugArray'], null, $fileName);
            unset($GLOBALS['debugArray']);
        }

        return true;
    }

    /**
     * 异步日志
     *
     * @param      $content
     * @param null $subject
     * @return bool
     * @throws \Exception
     */
    public static function asyncLog($keyName, $content, $title = null, $isEnd = false, $fileName = 'lumen')
    {
        if (!isset($GLOBALS[$keyName])) {
            $GLOBALS[$keyName] = array();
        }

        if ($title) {
            array_push($GLOBALS[$keyName], $title);
            array_push($GLOBALS[$keyName], '==========================');
        }

        if ($content) {
            array_push($GLOBALS[$keyName], print_r($content, true));
            array_push($GLOBALS[$keyName], '--------------------------');
        }

        if ($isEnd) {
            self::logInfo($GLOBALS[$keyName], null, $fileName);
            unset($GLOBALS[$keyName]);
        }

        return true;
    }

    /**
     * curl请求
     *
     * @author huangjinbing <373768442@qq.com>
     * @date   2018-07-02
     * @param string $url 访问的URL
     * @param string $post post数据(不填则为GET)
     * @param string $cookie 提交的$cookies
     * @param int $returnCookie 是否返回$cookies
     * @since  PM_1.0_zantui 
     * @return mixed|string
     */
    public static function curlRequest($url, $post = '', $contentType = 'application/json', $cookie = '', $returnCookie = 0)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_REFERER, "http://XXX");

        if (is_array($post)) {
            // 数组类型
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
        } else if ($post) {
            // json类型
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                "Content-Type: $contentType",
                'Content-Length: ' . strlen($post)
            ));
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        }

        if ($cookie) {
            curl_setopt($curl, CURLOPT_COOKIE, $cookie);
        }
        curl_setopt($curl, CURLOPT_HEADER, $returnCookie);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        if (curl_errno($curl)) {
            return curl_error($curl);
        }
        curl_close($curl);
        if ($returnCookie) {
            list($header, $body) = explode("\r\n\r\n", $data, 2);
            preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
            $info['cookie'] = substr($matches[1][0], 1);
            $info['content'] = $body;
            return $info;
        } else {
            return $data;
        }
    }

    /**
     * 检查请求参数
     *
     * @author huangjinbing <373768442@qq.com>
     * @date   2018/05/28
     * @param mixed $keys
     * @since  1.1.0 
     * @return \Laravel\Lumen\Application|mixed
     * @throws RequestException
     */
    public static function checkRequest($keys, $isOnly = true)
    {
        // 判断是否是数组
        if (!is_array($keys)) {
            $required[] = $keys;
        } else {
            $required = $keys;
        }
        // 检查必传参数
        $allRequest = request()->keys();
        foreach ($required as $requiredKey) {
            if (!in_array($requiredKey, $allRequest)) {
                $withoutKeys[] = $requiredKey;
            }
        }

        // 拼接错误参数
        if (!empty($withoutKeys)) {
            $message = '缺少参数';
            if (env('APP_ENV') != 'master') {
                $message .= ':' . implode(',', $withoutKeys);
            }
            throw new RequestException($message);
        }

        if ($isOnly) {
            return request($required);
        } else {
            return request()->all();
        }
    }

    /**
     * 保留两位小数
     *
     * @author ysg <18934006047@163.com>
     * @date   2018-06-21
     * @since  PM_1.0_zantui 
     * @param $price
     * @return string|bool
     */
    public static function formatPrice($price, $trimZero = true)
    {
        if ($trimZero) {
            return (string)floatval(substr(sprintf("%.3f", $price), 0, -1));
        }
        return substr(sprintf("%.3f", $price), 0, -1);
    }

    /**
     * 加密
     *
     * @author ysg <18934006047@163.com>
     * @date   2018-06-24
     * @since  PM_1.0_zantui 
     * @param $data
     * @return mixed
     */
    public static function dataEncrypt($data)
    {
        $key = self::ENCRYPT_KEY;
        ksort($data);
        return md5(http_build_query($data) . $key);
    }

    /**
     * 传参验证
     *
     * @author huangjinbing <373768442@qq.com>
     * @date   2018-06-08
     * @param array $data 需要验证的数组
     * @param array $rules 验证规则
     * @param string $messageKey 使用哪个板块的验证提示
     * @since  PM_1.0_zantui 
     * @throws \Exception
     */
    public static function dataValidator($data, $rules, $messageKey)
    {
        if (is_array($data)) {
            $messages = config("validator_message." . $messageKey);
            $validator = Validator::make($data, $rules, $messages);
            if ($validator->fails()) {
                $errorMessage = json_decode($validator->errors(), true);
                throw new ValidationException(array_first($errorMessage)[0] ?? '验证数据失败');
            }
        }
    }

    /**
     * 模拟生成token
     *
     * @author Jy马 <Majy999@outlook.com>
     * @date   2018/7/5 18:57
     * @since  PM_1.0_zantui
     * @return string
     */
    public static function setToken()
    {
        // 生成一个不会重复的字符串
        $str = md5(uniqid(md5(microtime(true)), true));
        $str = sha1($str);
        return $str;
    }

    /**
     * 过滤掉EmoJi表情
     *
     * @author ysg <18934006047@163.com>
     * @date   2018-08-18
     * @since  PM_1.0_agent_admin
     * @param $str
     * @return mixed
     */
    public static function filterEmoJi($str)
    {
        $str = preg_replace_callback('/./u', function (array $match) {
            return strlen($match[0]) >= 4 ? '' : $match[0];
        }, $str);

        return $str ?? '?';
    }

    /**
     * cos curl请求
     *
     * @param        $url
     * @param string $method
     * @param array $header
     * @param array $body
     * @return mixed
     */
    public static function requestWithHeader($url, $method = 'POST', $header = array(), $body = array())
    {
        //array_push($header, 'Accept:application/json');
        //array_push($header, 'Content-Type:application/json');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        switch ($method) {
            case "GET" :
                curl_setopt($ch, CURLOPT_HTTPGET, true);
                break;
            case "POST" :
                curl_setopt($ch, CURLOPT_POST, true);
                break;
            case "PUT" :
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                break;
            case "DELETE" :
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
        }
        curl_setopt($ch, CURLOPT_USERAGENT, 'SSTS Browser/1.0');
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        if (isset($body{3}) > 0) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }
        if (count($header) > 0) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        $ret = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($ret, true);

        return $data;
    }

    /**
     * 远程下载图片
     *
     * @author Jy马 <Majy999@outlook.com>
     * @date   2018/10/30 17:35
     * @param        $imageUrl
     * @param        $imageName
     * @since  PM_1.3_ws
     * @return string
     */
    public static function curlDownPic($imageUrl, $imageName = '')
    {
        $uploadDir = '/uploads/temp/';

        // 文件保存目录
        $fileDir = public_path() . $uploadDir;

        if (!is_dir($fileDir)) @mkdir($fileDir, 755, true);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $imageUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        $file = curl_exec($ch);
        curl_close($ch);
        $filename = $fileDir . time() . ($imageName ?: pathinfo($imageUrl, PATHINFO_BASENAME)) . '.png';
        $resource = fopen($filename, 'a');
        fwrite($resource, $file);
        fclose($resource);
        return $filename;
    }

    /**
     * 生成订单编号
     *
     * @param string $type C用户订单、P交易单、W提现单
     * @return string
     */
    public static function createOrderNumber($type = 'C')
    {
        // 当前年月日时分秒
        $year = substr(date('Y'), 2, 2);
        $orderNumber = $type . $year . date('mdHis');

        // 拼上4位随机数
        $orderNumber .= rand(1000, 9999);
        return self::checkOrderNumber($orderNumber, $type);
    }

    /**
     * 检验订单编码是否唯一
     *
     * @param string $orderNumber
     * @param string $type
     * @return string
     */
    protected static function checkOrderNumber($orderNumber, $type)
    {
        // 生成当前值
        $redisKey = config('rediskeys.order.hash.order_sns');

        // 缓存不存在，查询数据库，设置缓存
        if (!RedisTools::exists($redisKey)) {
            switch ($type) {
                case 'C':
                    $orderNumbers = CustomerOrder::pluck('order_sn')->toArray();
                    if (count($orderNumbers)) RedisTools::sadd($redisKey, $orderNumbers);
                    break;
                case 'W':
                    break;
                case 'P':
                    $tradeNumbers = PayTrade::pluck('trade_sn')->toArray();
                    if (count($tradeNumbers)) RedisTools::sadd($redisKey, $tradeNumbers);
                    break;
                case 'A':
                    $orderNumbers = AfterSaleOrder::pluck('order_sn')->toArray();
                    if (count($orderNumbers)) RedisTools::sadd($redisKey, $orderNumbers);
                    break;
            }
        }

        // 判断集合中该值是否存在
        if (RedisTools::sadd($redisKey, $orderNumber)) {
            return $orderNumber;
        } else {
            return self::createOrderNumber($type);
        }
    }

    /**
     * 时间对象转化
     *
     * @author huangjinbing
     * @date   2018-12-13
     * @param $timeObj
     * @since  PM_1.0_shopping
     * @return string
     */
    public static function formatTime($timeObj, $format = 'Y-m-d H:i:s')
    {
        if (!$timeObj) return '';
        return Carbon::parse($timeObj)->format($format);
    }

    /**
     * 生成提货单号
     */
    public static function createOrderCode()
    {
        $now = date('Y-m-d');
        $redisKey = config('rediskeys.order.hash.order_code');

        if (RedisTools::hexists($redisKey, $now)) {
            RedisTools::hincrby($redisKey, $now, 1);
        } else {
            RedisTools::hset($redisKey, $now, 1);
        }

        $orderCode = RedisTools::hget($redisKey, $now);
        return $orderCode;
    }

    /**
     * 清除缓存
     *
     * @author Jy马 <Majy999@outlook.com>
     * @date   2018/12/18 23:19
     * @param $tableName
     * @param $id
     * @since  PM_1.0_shopping
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public static function delObjectCache($tableName, $id)
    {
        $key = "{$GLOBALS['company_id']}:$tableName:$id";
        if (Cache::delete($key)) return true;
        return false;
    }

    /**
     * 转换为时间区间
     *
     * @author wareon <wareon@qq.com>
     * @date   2018/12/30 16:34
     * @param        $date
     * @param string $startTime
     * @param string $endTime
     * @since  PM_1.0_shopping
     * @return array
     */
    public static function getBetweenTime($date, $startTime = '', $endTime = '')
    {
        switch ($date) {
            case 'today':
                // 今天
                return [Carbon::today(), Carbon::tomorrow()->subSecond(1)];
            case 'yesterday':
                // 昨天
                return [Carbon::yesterday(), Carbon::today()->subSecond(1)];
            case 'week':
                // 7天
                return [Carbon::yesterday()->subDay(6), Carbon::today()->subSecond(1)];
            case 'month':
                // 30天
                return [Carbon::yesterday()->subDay(29), Carbon::today()->subSecond(1)];
            case 'season':
                // 90天
                return [Carbon::yesterday()->subDay(89), Carbon::today()->subSecond(1)];
            default:
                if (!empty($startTime) && !empty($endTime)) {
                    $startTime .= ' 00:00:00';
                    $endTime .= ' 23:59:59';
                }
                $startTime = Carbon::parse($startTime);
                $endTime = Carbon::parse($endTime);
                return [$startTime, $endTime];
        }
    }

    /**
     * 阿拉伯数字转汉字
     *
     * @author Jy马 <Majy999@outlook.com>
     * @date   2019/1/10 10:22
     * @param int $number 数字
     * @param bool $isRmb 是否是金额数据
     * @since  PM_1.0_shopping
     * @return string
     */
    public static function number2chinese($number, $isRmb = false)
    {
        // 判断正确数字
        if (!preg_match('/^-?\d+(\.\d+)?$/', $number)) {
            return 'number2chinese() wrong number';
        }
        list($integer, $decimal) = explode('.', $number . '.0');

        // 检测是否为负数
        $symbol = '';
        if (substr($integer, 0, 1) == '-') {
            $symbol = '负';
            $integer = substr($integer, 1);
        }
        if (preg_match('/^-?\d+$/', $number)) {
            $decimal = null;
        }
        $integer = ltrim($integer, '0');

        // 准备参数
        $numArr = ['', '一', '二', '三', '四', '五', '六', '七', '八', '九', '.' => '点'];
        $descArr = ['', '十', '百', '千', '万', '十', '百', '千', '亿', '十', '百', '千', '万亿', '十', '百', '千', '兆', '十', '百', '千'];
        if ($isRmb) {
            $number = substr(sprintf("%.5f", $number), 0, -1);
            $numArr = ['', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖', '.' => '点'];
            $descArr = ['', '拾', '佰', '仟', '万', '拾', '佰', '仟', '亿', '拾', '佰', '仟', '万亿', '拾', '佰', '仟', '兆', '拾', '佰', '仟'];
            $rmbDescArr = ['角', '分', '厘', '毫'];
        }

        // 整数部分拼接
        $integerRes = '';
        $count = strlen($integer);
        if ($count > max(array_keys($descArr))) {
            return 'number2chinese() number too large.';
        } else if ($count == 0) {
            $integerRes = '零';
        } else {
            for ($i = 0; $i < $count; $i++) {
                $n = $integer[$i];      // 位上的数
                $j = $count - $i - 1;   // 单位数组 $descArr 的第几位
                // 零零的读法
                $isLing = $i > 1                    // 去除首位
                    && $n !== '0'                   // 本位数字不是零
                    && $integer[$i - 1] === '0';    // 上一位是零
                $cnZero = $isLing ? '零' : '';
                $cnNum = $numArr[$n];
                // 单位读法
                $isEmptyDanwei = ($n == '0' && $j % 4 != 0)     // 是零且一断位上
                    || substr($integer, $i - 3, 4) === '0000';  // 四个连续0
                $descMark = isset($cnDesc) ? $cnDesc : '';
                $cnDesc = $isEmptyDanwei ? '' : $descArr[$j];
                // 第一位是一十
                if ($i == 0 && $cnNum == '一' && $cnDesc == '十') $cnNum = '';
                // 二两的读法
                $isChangeEr = $n > 1 && $cnNum == '二'       // 去除首位
                    && !in_array($cnDesc, ['', '十', '百'])  // 不读两\两十\两百
                    && $descMark !== '十';                   // 不读十两
                if ($isChangeEr) $cnNum = '两';
                $integerRes .= $cnZero . $cnNum . $cnDesc;
            }
        }

        // 小数部分拼接
        $decimalRes = '';
        $count = strlen($decimal);
        if ($decimal === null) {
            $decimalRes = $isRmb ? '整' : '';
        } else if ($decimal === '0') {
            $decimalRes = '零';
        } else if ($count > max(array_keys($descArr))) {
            return 'number2chinese() number too large.';
        } else {
            for ($i = 0; $i < $count; $i++) {
                if ($isRmb && $i > count($rmbDescArr) - 1) break;
                $n = $decimal[$i];
                $cnZero = $n === '0' ? '零' : '';
                $cnNum = $numArr[$n];
                $cnDesc = $isRmb ? $rmbDescArr[$i] : '';
                $decimalRes .= $cnZero . $cnNum . $cnDesc;
            }
        }
        // 拼接结果
        $res = $symbol . ($isRmb ?
                $integerRes . ($decimalRes === '零' ? '元整' : "元$decimalRes") :
                $integerRes . ($decimalRes === '' ? '' : "点$decimalRes"));
        return $res;
    }

    /**
     * 求两个已知经纬度之间的距离,单位为米
     *
     * @author Jy马 <Majy999@outlook.com>
     * @date   2019/1/22 16:42
     * @param $lng1
     * @param $lat1
     * @param $lng2
     * @param $lat2
     * @since  PM_1.0_shopping
     * @return float|int
     */
    public static function getDistance($lng1, $lat1, $lng2, $lat2)
    {
        if (!$lng1 && !$lat1) {
            $s = '';
        } else {
            // 将角度转为狐度
            $radLat1 = deg2rad($lat1); //deg2rad()函数将角度转换为弧度
            $radLat2 = deg2rad($lat2);
            $radLng1 = deg2rad($lng1);
            $radLng2 = deg2rad($lng2);
            $a = $radLat1 - $radLat2;
            $b = $radLng1 - $radLng2;
            $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.137 * 1000;
        }
        return $s;
    }

    /**
     * 距离处理
     *
     * @author Jy马 <Majy999@outlook.com>
     * @date   2019/1/22 16:53
     * @param $distance
     * @since  PM_1.0_shopping
     * @return string
     */
    public static function dealDistance($distance)
    {
        if (!$distance) {
            $distance = '';
        } else if ($distance > 1000) {
            $distance = substr(sprintf("%.3f", ($distance / 1000)), 0, -1) . '千米';
        } else {
            $distance = substr(sprintf("%.3f", $distance), 0, -1) . '米';
        }
        return $distance;
    }

    /**
     * 生成唯一商品编码
     *
     * @author Jy马 <Majy999@outlook.com>
     * @date   2019/3/12 19:44
     * @since  PM_2.0_shopping
     * @return string
     */
    public static function createGoodsSkuCode()
    {
        $goodsSkuCode = 'SKU' . Carbon::now()->format('His') . rand(1000, 9999);
        return self::_checkGoodsSkuCode($goodsSkuCode);
    }

    /**
     * 商品编码唯一性检测
     *
     * @author Jy马 <Majy999@outlook.com>
     * @date   2019/4/9 10:20
     * @param $goodsSkuCode
     * @since  PM_2.1_coupon
     * @return string
     */
    protected static function _checkGoodsSkuCode($goodsSkuCode)
    {
        // 生成当前值
        $redisKey = config('rediskeys.goods.hash.goods_sku_code');

        // 缓存不存在，查询数据库，设置缓存
        if (!RedisTools::exists($redisKey)) {
            $goodsSkuCodes = GoodsSku::pluck('goods_sku_code')->toArray();
            if (count($goodsSkuCodes)) RedisTools::sadd($redisKey, $goodsSkuCodes);
        }

        // 判断集合中该值是否存在
        if (RedisTools::sadd($redisKey, $goodsSkuCode)) {
            return $goodsSkuCode;
        } else {
            return self::createGoodsSkuCode();
        }
    }

    /**
     * 生成唯一商品编码(展示)
     *
     * @author lzz <1187215175@qq.com>
     * @date   2019/8/30 10:20
     * @param $goodsSkuEncodingCode
     * @return string
     */
    public static function createGoodsSkuEncodingCode()
    {
        $goodsSkuEncodingCode = 'SKU' . Carbon::now()->format('His') . rand(100000, 999999);
        return self::_checkGoodsSkuEncodingCode($goodsSkuEncodingCode);
    }

    /**
     * 商品编码唯一性检测(展示)
     *
     * @author lzz <1187215175@qq.com>
     * @date   2019/8/30 10:20
     * @param $goodsSkuEncodingCode
     * @return string
     */
    protected static function _checkGoodsSkuEncodingCode($goodsSkuEncodingCode)
    {
        // 生成当前值
        $redisKey = config('rediskeys.goods.hash.goods_sku_encoding_code');

        // 缓存不存在，查询数据库，设置缓存
        if (!RedisTools::exists($redisKey)) {
            $goodsSkuCodes = GoodsSku::pluck('goods_sku_encoding')->toArray();
            if (count($goodsSkuCodes)) RedisTools::sadd($redisKey, $goodsSkuCodes);
        }

        // 判断集合中该值是否存在
        if (RedisTools::sadd($redisKey, $goodsSkuEncodingCode)) {
            return $goodsSkuEncodingCode;
        } else {
            return self::createGoodsSkuEncodingCode();
        }
    }

    /**
     * 去掉小数00
     *
     * @author ysg <18934006047@163.com>
     * @date   2018/11/23 10:29
     * @param int $price
     * @return string
     */
    public static function fatPrice($price = 0)
    {
        $price = sprintf('%.2f', $price);
        return rtrim(rtrim($price, '0'), '.');
    }

    /**
     * 获取外网IP
     *
     * @author huangjinbing
     * @date   2019-03-15
     * @since  PM_1.0_scm
     * @return string
     */
    public static function getClientIp()
    {
        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $cip = $_SERVER["HTTP_CLIENT_IP"];
        } elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif (!empty($_SERVER["REMOTE_ADDR"])) {
            $cip = $_SERVER["REMOTE_ADDR"];
        }
        return $cip ?? '';
    }

    /**
     * 精度计算（最多只支持4位小数计算）
     *
     * @author Jy马 <Majy999@outlook.com>
     * @date   2019/3/27 16:18
     * @param $m
     * @param $n
     * @param $x
     * @since  PM_2.1_coupon
     * @return float|int|string
     */
    public static function calc($m, $n, $x)
    {
        $numCountM = 0;
        $numCountN = 0;
        $tempM = explode('.', $m);
        if (sizeof($tempM) > 1) {
            $decimal = end($tempM);
            $numCountM = strlen($decimal);
        }

        $tempN = explode('.', $n);
        if (sizeof($tempN) > 1) {
            $decimal = end($tempN);
            $numCountN = strlen($decimal);
        }

        if (($numCountM ?? 0) > ($numCountN ?? 0)) {
            $baseNum = pow(10, $numCountM ?? 0);
        } else {
            $baseNum = pow(10, $numCountN ?? 0);
        }

        $m = intval(round($m * $baseNum));
        $n = intval(round($n * $baseNum));

        switch ($x) {
            case '+':
                $response = $m + $n;
                break;
            case '-':
                $response = $m - $n;
                break;
            case '*':
                $response = $m * $n / $baseNum;
                break;
            case '/':
                if ($n != 0) {
                    $response = $m / $n;
                } else {
                    $response = '被除数不能为零';
                }
                break;
            default:
                $response = '参数传递错误';
                break;
        }
        return $response / $baseNum;
    }

    /**
     * 文件转base64
     *
     * @author huangjinbing
     * @date   2019-04-04
     * @param $file
     * @since  PM_2.1_fixed_activity
     * @return string
     */
    public static function fileToBase64($file)
    {
        $base64File = '';
        if (file_exists($file)) {
            $mimeType = mime_content_type($file) ?: 'image/png';
            $base64Data = base64_encode(file_get_contents($file));
            $base64File = 'data:' . $mimeType . ';base64,' . $base64Data;
        }
        return $base64File;
    }

    /**
     * 格式化整形
     *
     * @author ysg <18934006047@163.com>
     * @date   2018-06-21
     * @since  PM_1.0_zantui 
     * @param $value
     * @return int
     */
    public static function formatInt($value)
    {
        return (int)$value;
    }

    /**
     * 计算复购率
     *
     * @author chenjie <113157428@qq.com>
     * @date   2019-04-15
     * @since  PM_2.3.3_offline_activity 
     */
    public static function getRepurchaseRate($repurchasePersonCounts, $payPersonCounts)
    {
        $repeatRate = 0;
        if ($payPersonCounts > 0 && $repurchasePersonCounts > 0) {
            $repeatRate = $repurchasePersonCounts / $payPersonCounts;
        }
        return $repeatRate;
    }

    /**
     *  1 到 30 随机获取 9个不同的数字，返回数组类型
     *
     * @author chenjie <113157428@qq.com>
     * @date   2019-04-20
     */
    public static function randomHeadImages()
    {
        $dataArr = [];
        while (count($dataArr) < 9) {
            $value = mt_rand(1, 30);
            array_push($dataArr, $value);
            $dataArr = array_unique($dataArr);
        }
        return $dataArr;
    }

    /**
     *  数据中心
     *  获取开始时间和结束时间
     * @author chenjie <113157428@qq.com>
     */
    public static function getBetweenDate($data)
    {
        if (isset($data['date_type']) && !empty($data['date_type'])) {
            switch ($data['date_type']) {
                case 'today':
                    $data['start_date'] = $data['end_date'] = Carbon::now()->toDateString();
                    break;
                case 'yesterday':
                    $data['start_date'] = $data['end_date'] = Carbon::yesterday()->toDateString();
                    break;
                case 'week':
                    $data['start_date'] = Carbon::now()->subDay(7)->toDateString();
                    $data['end_date'] = Carbon::yesterday()->toDateString();
                    break;
                case 'half_month':
                    $data['start_date'] = Carbon::now()->subDay(15)->toDateString();
                    $data['end_date'] = Carbon::yesterday()->toDateString();
                    break;
                case 'month':
                    $data['start_date'] = Carbon::now()->subDay(30)->toDateString();
                    $data['end_date'] = Carbon::yesterday()->toDateString();
                    break;
            }
        }
        return $data;
    }

    /**
     *  数据中心
     *  获取开始时间和结束时间
     * @author chenjie <113157428@qq.com>
     */
    public static function getBetweenDateByType($data)
    {
        $startDate = Carbon::parse($data['start_date']);

        if (isset($data['date_type']) && isset($data['date_type'])) {

            if ((isset($data['spu']) && $data['spu']) || (isset($data['coss_time_show']) && $data['coss_time_show'])) {
                // 单个商品的
                switch ($data['date_type']) {
                    case 'day':
                        $data['start_date'] = $startDate->copy()->subDays(29)->toDateString();
                        $data['end_date'] = $startDate->copy()->toDateString();
                        break;
                    case 'week':
                        $data['start_date'] = $startDate->copy()->subWeeks(11)->startOfWeek()->toDateString();
                        $data['end_date'] = $startDate->copy()->endOfWeek()->toDateString();
                        break;
                    case 'month':
                        $data['start_date'] = $startDate->copy()->subMonths(11)->startOfMonth()->toDateString();
                        $data['end_date'] = $startDate->copy()->endOfMonth()->toDateString();
                        break;
                }
            } else {

                switch ($data['date_type']) {
                    case 'day':
                        $data['start_date'] = $data['start_date'];
                        $data['end_date'] = $data['start_date'];
                        break;
                    case 'week':
                        $data['start_date'] = $startDate->copy()->startOfWeek()->toDateString();
                        $data['end_date'] = $startDate->copy()->endOfWeek()->toDateString();
                        break;
                    case 'month':
                        $data['start_date'] = $startDate->copy()->startOfMonth()->toDateString();
                        $data['end_date'] = $startDate->copy()->endOfMonth()->toDateString();
                        break;
                }

            }

        }

        return $data;
    }

    /**
     * 填充空白日期数据
     * @param $data
     * @param $dayType
     */
    public static function fillEmptyDataInOtherDays(&$data, $startDate, $endDate, $defaultData)
    {
        $data = collect($data); // Convert

        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->startOfDay();

        $days = collect();

        while ($startDate->lte($endDate)) {
            $days->push($startDate->copy()->format('Y-m-d'));
            $startDate->addDay(); // Add day.
        };

        $days->diff($data->pluck('date'))->each(function ($day) use (&$data, $defaultData) {
//            $defaultData['date'] = $day;
//            $defaultData['year'] = self::formatTime($day, 'Y');
//            $defaultData['month'] = self::formatTime($day, 'm');
//            $defaultData['day'] = self::formatTime($day, 'd');
            $data->push($defaultData);
        });

        $data = $data->sortBy('year')
            ->sortBy('month')
            ->sortBy('week')
            ->sortBy('day')
            ->values()
            ->toArray();
        return $data;
    }

    /**
     * 填充空白日期数据
     * @param $data
     * @param $dayType
     */
    public static function fillEmptyTimeData(&$data, $dataType = 'day', $startDate, $defaultData, $subNum = 0)
    {
        $unionData = [];
        $timeArr = [];

        $data = $data ?? [];

        $date = Carbon::parse($startDate);

        switch ($dataType) {
            case 'day':
                $subNum = $subNum ? $subNum : 30;
                for ($i = $subNum; $i > 0; $i--) {
                    $time = $date->copy()->subDays($i);
                    $ishas = 0;
                    foreach ($data as $v) {
                        if ($time->year == $v['year'] && $time->month == $v['month'] && $time->day == $v['day']) {
                            $ishas = 1;
                        }
                    }
                    if (!$ishas) {
                        $defaultData['year'] = $time->year;
                        $defaultData['month'] = $time->month;
                        $defaultData['week'] = $time->weekOfYear;
                        $defaultData['day'] = $time->day;
                        array_push($data, $defaultData);
                    }
                }

                break;

            case 'week':
                $subNum = $subNum ? $subNum : 12;
                for ($i = $subNum; $i > 0; $i--) {
                    $time = $date->copy()->subWeeks($i);
                    $ishas = 0;
                    foreach ($data as $v) {
                        if ($time->year == $v['year'] && $time->weekOfYear == $v['week']) {
                            $ishas = 1;
                        }
                    }
                    if (!$ishas) {
                        $defaultData['year'] = $time->copy()->startOfWeek()->year;
                        $defaultData['month'] = $time->copy()->startOfWeek()->month;
                        $defaultData['week'] = $time->weekOfYear;
                        $defaultData['day'] = $time->copy()->startOfWeek()->day;
                        array_push($data, $defaultData);
                    }
                }

                break;
            case 'month':
                $subNum = $subNum ? $subNum : 12;
                for ($i = $subNum; $i > 0; $i--) {
                    $time = $date->copy()->subMonths($i);
                    $ishas = 0;
                    foreach ($data as $v) {
                        if ($time->year == $v['year'] && $time->month == $v['month']) {
                            $ishas = 1;
                        }
                    }
                    if (!$ishas) {
                        $defaultData['year'] = $time->copy()->startOfMonth()->year;
                        $defaultData['month'] = $time->copy()->startOfMonth()->month;
                        $defaultData['week'] = 0;
                        $defaultData['day'] = 0;
                        array_push($data, $defaultData);
                    }
                }
                break;
        }
        return $data;
    }

    /**
     * 填充空白日期数据
     * @param $data
     * @param $dayType
     */
    public static function fillTimeArr($dataType = 'day', $startDate,$subNum = 0)
    {
        $data = [];
        $date = Carbon::parse($startDate);
        switch ($dataType) {
            case 'day':
                $subNum = $subNum ? $subNum : 29;
                for ($i = $subNum; $i >= 0; $i--) {
                    $time = $date->copy()->subDays($i);
                    $timeArr = [
                        'year' =>$time->copy()->year,
                        'month' =>$time->copy()->month,
                        'week' =>0,
                        'day' =>$time->copy()->day,
                    ];
                    array_push($data, $timeArr);
                }
                break;

            case 'week':
                $subNum = $subNum ? $subNum : 11;
                for ($i = $subNum; $i >= 0; $i--) {
                    $time = $date->copy()->subWeeks($i);
                    $timeArr = [
                        'year' =>$time->copy()->endOfWeek()->year,
                        'month' =>0,
                        'week' =>$time->weekOfYear,
                        'day' =>0,
                    ];
                    array_push($data, $timeArr);
                }
                break;
            case 'month':
                $subNum = $subNum ? $subNum : 11;
                for ($i = $subNum; $i >= 0; $i--) {
                    $time = $date->copy()->subMonthsNoOverflow($i);
                    $timeArr = [
                        'year' =>$time->year,
                        'month' =>$time->month,
                        'week' =>0,
                        'day' =>0,
                    ];
                    array_push($data, $timeArr);
                }
                break;
        }
        return $data;
    }

    /**
     * 转换URL链接HTTP为HTTPS
     *
     * @param $url
     * @return mixed
     * @author chengciming
     * @date   2019/7/16
     */
    public static function httpToHttps($url)
    {
        if (empty($url)) return $url;
        $url = trim($url);
        $prefix = substr($url, 0, 7);
        if (strtolower($prefix) == 'http://') {
            $url = 'https://' . substr($url, 7);
        }
        return $url;
    }

    /**
     * 日转换为星期
     *
     * @author huangjinbing
     * @date   2019-08-15
     * @param $day
     * @since  PM_2.9.4
     * @return string|void
     */
    public static function dayOfWeek($day)
    {
        if (!$day) return;
        $weekNum = Carbon::parse($day)->dayOfWeek;
        switch ($weekNum) {
            case '0':
                $weekCn = '星期日';
                break;
            case '1':
                $weekCn = '星期一';
                break;
            case '2':
                $weekCn = '星期二';
                break;
            case '3':
                $weekCn = '星期三';
                break;
            case '4':
                $weekCn = '星期四';
                break;
            case '5':
                $weekCn = '星期五';
                break;
            case '6':
                $weekCn = '星期六';
                break;
            default:
                $weekCn = '未知';
                break;
        }
        return $weekCn;
    }
}
