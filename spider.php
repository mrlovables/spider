<?php
// set_time_limit(0);
// require 'CliProgressBar.php';
/**
 * Class CliProgressBar
 * @package Dariuszp
 */
class CliProgressBar
{
    const COLOR_CODE_FORMAT = "\033[%dm";

    /**
     * @var int
     */
    protected $barLength = 40;

    /**
     * @var array|bool
     */
    protected $color = false;

    /**
     * @var int
     */
    protected $steps = 100;

    /**
     * @var int
     */
    protected $currentStep = 0;

    /**
     * @var string
     */
    protected $detail = "";

    /**
     * @var string
     */
    protected $charEmpty = '░';

    /**
     * @var string
     */
    protected $charFull = '▓';
    /**
     * @var string
     */
    protected $defaultCharEmpty = '░';

    /**
     * @var string
     */
    protected $defaultCharFull = '▓';

    /**
     * @var string
     */
    protected $alternateCharEmpty = '_';

    /**
     * @var string
     */
    protected $alternateCharFull = 'X';

    public function __construct($steps = 100, $currentStep = 0, $details = "", $forceDefaultProgressBar = false)
    {
        $this->setSteps($steps);
        $this->setProgressTo($currentStep);
        $this->setDetails($details);

        // Windows terminal is unable to display utf characters and colors
        if (!$forceDefaultProgressBar && strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $this->displayDefaultProgressBar();
        }
    }

    /**
     * @param int $currentStep
     * @return $this
     */
    public function setProgressTo($currentStep)
    {
        $this->setCurrentstep($currentStep);
        return $this;
    }

    /**
     * @return $this
     */
    public function displayDefaultProgressBar()
    {
        $this->charEmpty = $this->defaultCharEmpty;
        $this->charFull  = $this->defaultCharFull;
        return $this;
    }

    /**
     * @return $this
     */
    public function setColorToDefault()
    {
        $this->color = false;
        return $this;
    }

    public function setColorToBlack()
    {
        return $this->setColor(30, 39);
    }

    /**
     * @param $start
     * @param $end
     * @return $this
     */
    protected function setColor($start, $end)
    {
        $this->color = array(
            sprintf(self::COLOR_CODE_FORMAT, $start),
            sprintf(self::COLOR_CODE_FORMAT, $end),
        );
        return $this;
    }

    public function setColorToRed()
    {
        return $this->setColor(31, 39);
    }

    public function setColorToGreen()
    {
        return $this->setColor(32, 39);
    }

    public function setColorToYellow()
    {
        return $this->setColor(33, 39);
    }

    public function setColorToBlue()
    {
        return $this->setColor(34, 39);
    }

    public function setColorToMagenta()
    {
        return $this->setColor(35, 39);
    }

    public function setColorToCyan()
    {
        return $this->setColor(36, 39);
    }

    public function setColorToWhite()
    {
        return $this->setColor(37, 39);
    }

    /**
     * @return string
     */
    public function getDefaultCharEmpty()
    {
        return $this->defaultCharEmpty;
    }

    /**
     * @param string $defaultCharEmpty
     */
    public function setDefaultCharEmpty($defaultCharEmpty)
    {
        $this->defaultCharEmpty = $defaultCharEmpty;
    }

    /**
     * @return string
     */
    public function getDefaultCharFull()
    {
        return $this->defaultCharFull;
    }

    /**
     * @param string $defaultCharFull
     */
    public function setDefaultCharFull($defaultCharFull)
    {
        $this->defaultCharFull = $defaultCharFull;
    }

    /**
     * @return $this
     */
    public function displayAlternateProgressBar()
    {
        $this->charEmpty = $this->alternateCharEmpty;
        $this->charFull  = $this->alternateCharFull;
        return $this;
    }

    /**
     * @param int $currentStep
     * @return $this
     */
    public function addCurrentStep($currentStep)
    {
        $this->currentStep += intval($currentStep);
        return $this;
    }

    /**
     * @return string
     */
    public function getCharEmpty()
    {
        return $this->charEmpty;
    }

    /**
     * @param string $charEmpty
     * @return $this
     */
    public function setCharEmpty($charEmpty)
    {
        $this->charEmpty = $charEmpty;
        return $this;
    }

    /**
     * @return string
     */
    public function getCharFull()
    {
        return $this->charFull;
    }

    /**
     * @param string $charFull
     * @return $this
     */
    public function setCharFull($charFull)
    {
        $this->charFull = $charFull;
        return $this;
    }

    /**
     * @return string
     */
    public function getAlternateCharEmpty()
    {
        return $this->alternateCharEmpty;
    }

    /**
     * @param string $alternateCharEmpty
     * @return $this
     */
    public function setAlternateCharEmpty($alternateCharEmpty)
    {
        $this->alternateCharEmpty = $alternateCharEmpty;
        return $this;
    }

    /**
     * @return string
     */
    public function getAlternateCharFull()
    {
        return $this->alternateCharFull;
    }

    /**
     * @param string $alternateCharFull
     * @return $this
     */
    public function setAlternateCharFull($alternateCharFull)
    {
        $this->alternateCharFull = $alternateCharFull;
        return $this;
    }

    /**
     * @param string $details
     * @return $this
     */
    public function setDetails($details)
    {
        $this->detail = $details;
        return $this;
    }

    public function getDetails()
    {
        return $this->detail;
    }

    /**
     * @param int $step
     * @param bool $display
     * @return $this
     */
    public function progress($step = 1, $display = true)
    {
        $step = intval($step);
        $this->setCurrentstep($this->getCurrentStep() + $step);

        if ($display) {
            $this->display();
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getCurrentStep()
    {
        return $this->currentStep;
    }

    /**
     * @param int $currentStep
     * @return $this
     */
    public function setCurrentStep($currentStep)
    {
        $currentStep = intval($currentStep);
        if ($currentStep < 0) {
            throw new \InvalidArgumentException('Current step must be 0 or above');
        }

        $this->currentStep = $currentStep;
        if ($this->currentStep > $this->getSteps()) {
            $this->currentStep = $this->getSteps();
        }
        return $this;
    }

    public function display()
    {
        print $this->draw();
    }

    /**
     * @return string
     */
    public function draw()
    {
        $fullValue  = floor($this->getCurrentStep() / $this->getSteps() * $this->getBarLength());
        $emptyValue = $this->getBarLength() - $fullValue;
        $prc        = number_format(($this->getCurrentStep() / $this->getSteps()) * 100, 1, '.', ' ');

        $colorStart = '';
        $colorEnd   = '';
        if ($this->color) {
            $colorStart = $this->color[0];
            $colorEnd   = $this->color[1];
        }

        $userDetail = $this->getDetails();
        $userDetail = ((strlen($userDetail) > 1) ? "{$userDetail} " : "");
        $bar        = sprintf("%4\$s%5\$s %3\$.1f%% (%1\$d/%2\$d)", $this->getCurrentStep(), $this->getSteps(), $prc, str_repeat($this->charFull, $fullValue), str_repeat($this->charEmpty, $emptyValue));
        return sprintf("\r%s%s%s%s", $colorStart, $userDetail, $bar, $colorEnd);
    }

    /**
     * @return int
     */
    public function getSteps()
    {
        return $this->steps;
    }

    /**
     * @param int $steps
     * @return $this
     */
    public function setSteps($steps)
    {
        $steps = intval($steps);
        if ($steps < 0) {
            throw new \InvalidArgumentException('Steps amount must be 0 or above');
        }

        $this->steps = intval($steps);

        $this->setCurrentStep($this->getCurrentStep());

        return $this;
    }

    /**
     * @return int
     */
    public function getBarLength()
    {
        return $this->barLength;
    }

    /**
     * @param $barLength
     * @return $this
     */
    public function setBarLength($barLength)
    {
        $barLength = intval($barLength);
        if ($barLength < 1) {
            throw new \InvalidArgumentException('Progress bar length must be above 0');
        }
        $this->barLength = $barLength;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->draw();
    }

    /**
     * Alias to new line (nl)
     */
    public function end()
    {
        $this->nl();
    }

    /**
     * display new line
     */
    public function nl()
    {
        print "\n";
    }
}

/**
 *  爬虫
 */
class Spider
{
    //curl 伪造useragent
    private $useragent = [
        'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)',
        'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.2)',
        'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)',
        'Mozilla/5.0 (Windows; U; Windows NT 5.2) Gecko/2008070208 Firefox/3.0.1',
        'Opera/9.27 (Windows NT 5.2; U; zh-cn)',
        'Opera/8.0 (Macintosh; PPC Mac OS X; U; en)',
        'Mozilla/5.0 (Windows; U; Windows NT 5.2) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.2.149.27 Safari/525.13 ',
        'Mozilla/5.0 (Windows; U; Windows NT 5.2) AppleWebKit/525.13 (KHTML, like Gecko) Version/3.1 Safari/525.13',
    ];

    private $url = 'https://sclub.jd.com/comment/productPageComments.action?callback=fetchJSON_comment98vv1822&productId=%u&score=0&sortType=5&page=%u&pageSize=10&isShadowSku=0&fold=1';

    private $maxPage = 100; //

    public function __construct()
    {
        $this->setcookieFile();
    }

    /**
     * 正负数互转(兼容小数)
     */
    private function abs_conversion($num = 0)
    {
        return $num > 0 ? -1 * $num : abs($num);
    }

    /**
     * 下载远程文件
     * @param    string                   $url     地址
     * @param    string                   $local   本地文件地址
     * @param    integer                  $timeout 超时时间
     * @return   boolean
     */
    private function downRemoteFile($url, $local, $timeout = 10)
    {
        // echo $url, PHP_EOL, $local;exit;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        ob_start();
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($code != 200) {
            ob_end_clean();
            return false;
        }
        $content = ob_get_contents();
        ob_end_clean();
        $fp = @fopen($local, 'a'); //将文件绑定到流
        if ($fp == false) {
            echo '[NOTICE] ', date('Y-m-d H:i:s'), ' 下载失败:', PHP_EOL, $url;
        }
        fwrite($fp, $content); //写入文件
        return true;
    }

    /**
     * 创建cookie文件
     */
    private function setcookieFile()
    {
        $cookiefile = getcwd() . "/cookie.txt"; //创建一个用于存放cookie信息的临时文件,
        if (!file_exists($cookiefile)) {
            $file = @file_put_contents($cookiefile, "");
        }
    }

    /**
     * 文件夹操作
     * @param    string                   $dir 文件夹路径
     * @param    integer                  $mod 1:判断是否文件夹 2:创建文件夹
     */
    private function isDir($dir, $mod = 1)
    {
        if ($mod == 1) {
            if (is_dir($dir)) {
                return true;
            }
            return false;
        }
        if ($mod == 2) {
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
        }
    }

    /**
     * 创建IP地址
     */
    private function createIp()
    {
        $ipHeader = [59, 60, 62, 63, 64, 66, 101, 106, 113, 122, 163, 171, 182, 202, 204, 211, 218, 221, 222];
        $ipKey    = array_rand($ipHeader);
        $ip       = $ipKey . '.' . mt_rand(5, 254) . '.' . mt_rand(5, 254) . '.' . mt_rand(5, 254);
        return (string) $ip;
    }

    /**
     * curl
     */
    private function curlRequest($url)
    {
        $ch = curl_init();
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept-Encoding: gzip, deflate'));
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
        // curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOBODY, 0);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 300);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //获取数据返回流形式
        curl_setopt($ch, CURLOPT_AUTOREFERER, true); //重定向时，自动设置header中的Referer:信息
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); //启用时会将服务器服务器返回的"Location: "放在header中递归的返回给服务器，使用CURLOPT_MAXREDIRS可以限定递归返回的数量

        // 设置iP和useragent
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0)');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:220.168.7.99', 'CLIENT-IP:220.168.7.99')); //构造IP
        curl_setopt($ch, CURLOPT_REFERER, "https://item.jd.com"); //构造来路
        // curl_setopt($ch, CURLOPT_USERAGENT, array_rand($useragent));

        // 设置代理
        //curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
        //curl_setopt($ch, CURLOPT_PROXY, '218.213.168.131:80');
        //curl_setopt($ch, CURLOPT_PROXYUSERPWD, 'user:password');

        // 对于cookie保存
        curl_setopt($ch, CURLOPT_COOKIESESSION, true);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiefile); //关闭连接时，将服务器端返回的cookie保存在以下文件中
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiefile);

        //释放curl句柄
        $output = curl_exec($ch);
        $info   = curl_getinfo($ch);
        curl_close($ch);
        //转换字符编码
        $output = mb_convert_encoding($output, 'UTF-8', 'GBK');
        // $output = mb_convert_encoding($output, 'UTF-8', 'UTF-8,GBK,GB2312,BIG5');
        return $output;
    }

    /**
     * 处理CURL返回结果
     */
    private function dealOutput($output)
    {
        //截取json
        $start  = strpos($output, '(');
        $output = substr($output, $start + 1);
        $len    = strlen($output);
        $end    = strrpos($output, ')');
        $output = substr($output, 0, $this->abs_conversion($len - $end));

        $output   = json_decode($output, true);
        $comments = $output['comments'];
        //获取商品名称
        $name = $comments[0]['referenceName'];
        //截取所有商品图片
        $commentsImgs = array_column($comments, 'images');
        $imgLists     = [];
        foreach ($commentsImgs as $key => $value) {
            $tmp      = array_column($value, 'imgUrl');
            $imgLists = array_merge($imgLists, $tmp);
        }
        foreach ($imgLists as $key => &$value) {
            $reg   = "/(n0\/s)([0-9])+x([0-9])+(_jfs)/";
            $value = 'https:' . preg_replace($reg, 'shaidan/s616x405_jfs', $value);
        }
        $data = [
            'name'   => $name,
            'images' => $imgLists,
        ];
        return $data;
    }

    /**
     * 从文件读取爬取链接
     */
    private function getFileLine()
    {
        $file = fopen("url.txt", "r");
        $urls = [];
        $i    = 0;
        //输出文本中所有的行，直到文件结束为止。
        while (!feof($file)) {
            $urls[$i] = fgets($file); //fgets()函数从文件指针中读取一行
            $i++;
        }
        fclose($file);
        $urls = array_filter($urls);
        if (empty($urls)) {
            exit('没有获取到链接参数...' . PHP_EOL);
        }
        return $urls;
    }

    /**
     * 获取京东商品ID
     */
    private function getProductId($url)
    {
        if (!preg_match("/jd\.com/i", $url)) {
            return false;
        }
        preg_match('/\/\d+\.html/i', $url, $tmp);
        if (empty($tmp)) {
            return false;
        }
        $id = preg_replace('/\D/s', '', $tmp[0]);
        if (!$id) {
            return false;
        }
        return $id;
    }

    /**
     * 获取所有评论接口url
     */
    private function getCommentsUrl()
    {
        $urls     = $this->getFileLine();
        $comments = [];
        foreach ($urls as $key => $value) {
            $productId = $this->getProductId($value);
            if ($productId === false) {
                continue;
            }
            $i = 0;
            while ($i < $this->maxPage) {
                $comments[$productId][$i]['page'] = $i;
                $comments[$productId][$i]['url']  = sprintf($this->url, $productId, $i);
                $i++;
            }
        }
        if (empty($comments)) {
            exit('没有获取评论接口信息...' . PHP_EOL);
        }
        return $comments;
    }

    /**
     * 执行
     */
    public function run()
    {
        $urls = $this->getCommentsUrl();
        foreach ($urls as $k1 => $v1) {
            foreach ($v1 as $k2 => $v2) {
                $output = $this->curlRequest($v2['url']);
                $output = $this->dealOutput($output);
                echo '正在处理[' . $output['name'] . ']评论页第' . $v2['page'] . '页内容...', PHP_EOL;
                $path = getcwd() . '/output/' . $output['name'] . '/第' . $v2['page'] . '页'; //设置商品文件夹名称
                if ($this->isDir($path) === false) {
                    $this->isDir($path, 2);
                }
                $totalLen = count($output['images']);
                $bar      = new CliProgressBar(100, 0);
                $bar->setBarLength(60);
                $bar->display();
                $bar->setColorToGreen();
                foreach ($output['images'] as $k3 => $v3) {
                    $this->downRemoteFile($v3, $path . '/' . $k3 . '.jpg');
                    $currLen  = $k3 + 1;
                    $progress = floor(($currLen / $totalLen) * 100);
                    $bar->setProgressTo($progress);
                    $bar->progress();
                }
                $bar->end();
            }
        }
    }
}

$spider = new Spider();
$rs     = $spider->run();
