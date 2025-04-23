<?php
// 应用公共文件
use app\common\service\FileService;
use think\helper\Str;

/**
 * @notes 生成密码加密密钥
 * @param string $plaintext
 * @param string $salt
 * @return string
 * @author 段誉
 * @date 2021/12/28 18:24
 */
function create_password(string $plaintext, string $salt) : string
{
    return md5($salt . md5($plaintext . $salt));
}


/**
 * @notes 随机生成token值
 * @param string $extra
 * @return string
 * @author 段誉
 * @date 2021/12/28 18:24
 */
function create_token(string $extra = '') : string
{
    $salt = env('project.unique_identification', 'likeadmin');
    $encryptSalt = md5( $salt . uniqid());
    return md5($salt . $extra . time() . $encryptSalt);
}


/**
 * @notes 截取某字符字符串
 * @param $str
 * @param string $symbol
 * @return string
 * @author 段誉
 * @date 2021/12/28 18:24
 */
function substr_symbol_behind($str, $symbol = '.') : string
{
    $result = strripos($str, $symbol);
    if ($result === false) {
        return $str;
    }
    return substr($str, $result + 1);
}


/**
 * @notes 对比php版本
 * @param string $version
 * @return bool
 * @author 段誉
 * @date 2021/12/28 18:27
 */
function compare_php(string $version) : bool
{
    return version_compare(PHP_VERSION, $version) >= 0 ? true : false;
}


/**
 * @notes 检查文件是否可写
 * @param string $dir
 * @return bool
 * @author 段誉
 * @date 2021/12/28 18:27
 */
function check_dir_write(string $dir = '') : bool
{
    $route = root_path() . '/' . $dir;
    return is_writable($route);
}


/**
 * 多级线性结构排序
 * 转换前：
 * [{"id":1,"pid":0,"name":"a"},{"id":2,"pid":0,"name":"b"},{"id":3,"pid":1,"name":"c"},
 * {"id":4,"pid":2,"name":"d"},{"id":5,"pid":4,"name":"e"},{"id":6,"pid":5,"name":"f"},
 * {"id":7,"pid":3,"name":"g"}]
 * 转换后：
 * [{"id":1,"pid":0,"name":"a","level":1},{"id":3,"pid":1,"name":"c","level":2},{"id":7,"pid":3,"name":"g","level":3},
 * {"id":2,"pid":0,"name":"b","level":1},{"id":4,"pid":2,"name":"d","level":2},{"id":5,"pid":4,"name":"e","level":3},
 * {"id":6,"pid":5,"name":"f","level":4}]
 * @param array $data 线性结构数组
 * @param string $symbol 名称前面加符号
 * @param string $name 名称
 * @param string $id_name 数组id名
 * @param string $parent_id_name 数组祖先id名
 * @param int $level 此值请勿给参数
 * @param int $parent_id 此值请勿给参数
 * @return array
 */
function linear_to_tree($data, $sub_key_name = 'sub', $id_name = 'id', $parent_id_name = 'pid', $parent_id = 0)
{
    $tree = [];
    foreach ($data as $row) {
        if ($row[$parent_id_name] == $parent_id) {
            $temp = $row;
            $child = linear_to_tree($data, $sub_key_name, $id_name, $parent_id_name, $row[$id_name]);
            if ($child) {
                $temp[$sub_key_name] = $child;
            }
            $tree[] = $temp;
        }
    }
    return $tree;
}


/**
 * @notes 删除目标目录
 * @param $path
 * @param $delDir
 * @return bool|void
 * @author 段誉
 * @date 2022/4/8 16:30
 */
function del_target_dir($path, $delDir)
{
    //没找到，不处理
    if (!file_exists($path)) {
        return false;
    }

    //打开目录句柄
    $handle = opendir($path);
    if ($handle) {
        while (false !== ($item = readdir($handle))) {
            if ($item != "." && $item != "..") {
                if (is_dir("$path/$item")) {
                    del_target_dir("$path/$item", $delDir);
                } else {
                    unlink("$path/$item");
                }
            }
        }
        closedir($handle);
        if ($delDir) {
            return rmdir($path);
        }
    } else {
        if (file_exists($path)) {
            return unlink($path);
        }
        return false;
    }
}


/**
 * @notes 下载文件
 * @param $url
 * @param $saveDir
 * @param $fileName
 * @return string
 * @author 段誉
 * @date 2022/9/16 9:53
 */
function download_file($url, $saveDir, $fileName)
{
    if (!file_exists($saveDir)) {
        mkdir($saveDir, 0775, true);
    }
    $fileSrc = $saveDir . $fileName;
    file_exists($fileSrc) && unlink($fileSrc);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    $file = curl_exec($ch);
    curl_close($ch);
    $resource = fopen($fileSrc, 'a');
    fwrite($resource, $file);
    fclose($resource);
    if (filesize($fileSrc) == 0) {
        unlink($fileSrc);
        return '';
    }
    return $fileSrc;
}


/**
 * @notes 去除内容图片域名
 * @param $content
 * @return array|string|string[]
 * @author 段誉
 * @date 2022/9/26 10:43
 */
function clear_file_domain($content)
{
    $fileUrl = FileService::getFileUrl();
    $pattern = '/<img[^>]*\bsrc=["\']'.preg_quote($fileUrl, '/').'([^"\']+)["\']/i';
    return preg_replace($pattern, '<img src="$1"', $content);
}

/**
 * @notes 设置内容图片域名
 * @param $content
 * @return array|string|string[]|null
 * @author 段誉
 * @date 2024/2/5 16:36
 */
function get_file_domain($content)
{
    $fileUrl = FileService::getFileUrl();
    $imgPreg = '/(<img .*?src=")(?!https?:\/\/)([^"]*)(".*?>)/is';
    $videoPreg = '/(<video .*?src=")(?!https?:\/\/)([^"]*)(".*?>)/is';
    $content = preg_replace($imgPreg, "\${1}$fileUrl\${2}\${3}", $content);
    $content = preg_replace($videoPreg, "\${1}$fileUrl\${2}\${3}", $content);
    return $content;
}

/**
 * @notes uri小写
 * @param $data
 * @return array|string[]
 * @author 段誉
 * @date 2022/7/19 14:50
 */
function lower_uri($data)
{
    if (!is_array($data)) {
        $data = [$data];
    }
    return array_map(function ($item) {
        return strtolower(Str::camel($item));
    }, $data);
}


/**
 * @notes 获取无前缀数据表名
 * @param $tableName
 * @return mixed|string
 * @author 段誉
 * @date 2022/12/12 15:23
 */
function get_no_prefix_table_name($tableName)
{
    $tablePrefix = config('database.connections.mysql.prefix');
    $prefixIndex = strpos($tableName, $tablePrefix);
    if ($prefixIndex !== 0 || $prefixIndex === false) {
        return $tableName;
    }
    $tableName = substr_replace($tableName, '', 0, strlen($tablePrefix));
    return trim($tableName);
}


/**
 * @notes 生成编码
 * @param $table
 * @param $field
 * @param string $prefix
 * @param int $randSuffixLength
 * @param array $pool
 * @return string
 * @author 段誉
 * @date 2023/2/23 11:35
 */
function generate_sn($table, $field, $prefix = '', $randSuffixLength = 4, $pool = []) : string
{
    $suffix = '';
    for ($i = 0; $i < $randSuffixLength; $i++) {
        if (empty($pool)) {
            $suffix .= rand(0, 9);
        } else {
            $suffix .= $pool[array_rand($pool)];
        }
    }
    $sn = $prefix . date('YmdHis') . $suffix;
    if (app()->make($table)->where($field, $sn)->find()) {
        return generate_sn($table, $field, $prefix, $randSuffixLength, $pool);
    }
    return $sn;
}


/**
 * @notes 格式化金额
 * @param $float
 * @return int|mixed|string
 * @author 段誉
 * @date 2023/2/24 11:20
 */
function format_amount($float)
{
    if ($float == intval($float)) {
        return intval($float);
    } elseif ($float == sprintf('%.1f', $float)) {
        return sprintf('%.1f', $float);
    }
    return $float;
}

    /**
     * @notes 日志重写功能
     * @param $prefix文件类型
     * @param string $start 0 正常记录,1开始,2结束
     * @param null $data
     * @param null  $tt 标题
     * @return array|int|mixed|string
     */
if(!function_exists('addLog')){
    function addLog($prefix="", $start = 0,$data= '',$tt="",$uid="")
    {
        $t = date("Ymd",time()).'/'.$prefix;
        $shi = date("H",time());
        $day = date("Y-m-d H:i:s",time());
        if(empty($prefix)){
            $prefix="system";
        }
        if(is_array($data)){
            $data=json_encode($data,JSON_UNESCAPED_UNICODE);
        }
        $t=$t.'/'.$shi;
        
        if(!empty($uid)){
            $t=$t.'/'.$uid;
        };
        
        $dir=iconv("UTF-8", "GBK", app()->getRootPath().'runtime'. '/' .'customLog'.  '/' .$t);
        if (!file_exists($dir)) {
            @mkdir($dir, 0777, true);
        }
        
        // 创建文件
        $file = fopen($dir."/{$prefix}.log","a+");
        
        if($start==1){
            fwrite($file,"\n");
            fwrite($file,"\n");
        }
        fwrite($file,"╔========================[$day]========================╗\n");
        if($start==1){
            fwrite($file,"|                               ".($tt?$tt:"日志开始")."\n");
        }
        if($start==2){
            fwrite($file,"|                               ".($tt?$tt:"日志结束")."\n");
        }
        if(!empty($tt)){
            fwrite($file,"   ┌---------------------------------------------------------------┑\n");
            fwrite($file,"   |                              {$tt}\n");
            fwrite($file,"   ┗---------------------------------------------------------------┛\n");
        }
        if(!empty($data)){
            fwrite($file,$data."\n\n");
        }
        fwrite($file,"╚=====================================================================╝\n");
        
    }
}


/**
 * 获取客户端真实IP地址
 * @return string IP地址
 */
if (!function_exists('getClientIP')) {
  function getClientIP() {
    $ip = false;
    //客户端IP 或 NONE
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    //多重代理服务器下的客户端真实IP地址（可能伪造）,如果没有使用代理，此字段为空
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ips = explode (', ', $_SERVER['HTTP_X_FORWARDED_FOR']);
      if ($ip) { 
        array_unshift($ips, $ip);
        $ip = false; 
      }
      for ($i = 0; $i < count($ips); $i++) {
        $ip = $ips[$i];
        break;
      }
    }
    //客户端IP 或 (最后一个)代理服务器 IP
    return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
  }
}
/**
 * 获取客户端真实IP地址
 * @return string IP地址
 */
if (!function_exists('resultArray')) {
  function resultArray($code=0,$msg="",$data=[]) {
    return array("code"=>$code,"msg"=>$msg,"data"=>$data);
  }
}

/**
 * 生成随机字符串，数字，大小写字母随机组合
 *
 * @param int $length  长度
 * @param int $type    类型，1 纯数字，2 纯小写字母，3 纯大写字母，4 数字和小写字母，5 数字和大写字母，6 大小写字母，7 数字和大小写字母
 */
function random($table,$field, $type = 1,$length = 8)
{
    // 取字符集数组
    $number = range(0, 9);
    $lowerLetter = range('a', 'z');
    $upperLetter = range('A', 'Z');
    // 根据type合并字符集
    if ($type == 1) {
        $charset = $number;
    } elseif ($type == 2) {
        $charset = $lowerLetter;
    } elseif ($type == 3) {
        $charset = $upperLetter;
    } elseif ($type == 4) {
        $charset = array_merge($number, $lowerLetter);
    } elseif ($type == 5) {
        $charset = array_merge($number, $upperLetter);
    } elseif ($type == 6) {
        $charset = array_merge($lowerLetter, $upperLetter);
    } elseif ($type == 7) {
        $charset = array_merge($number, $lowerLetter, $upperLetter);
    } else {
        $charset = $number;
    }
    $str = '';
    // 生成字符串
    for ($i = 0; $i < $length; $i++) {
        $str .= $charset[mt_rand(0, count($charset) - 1)];
        // 验证规则
        if ($type == 4 && strlen($str) >= 2) {
            if (!preg_match('/\d+/', $str) || !preg_match('/[a-z]+/', $str)) {
                $str = substr($str, 0, -1);
                $i = $i - 1;
            }
        }
        if ($type == 5 && strlen($str) >= 2) {
            if (!preg_match('/\d+/', $str) || !preg_match('/[A-Z]+/', $str)) {
                $str = substr($str, 0, -1);
                $i = $i - 1;
            }
        }
        if ($type == 6 && strlen($str) >= 2) {
            if (!preg_match('/[a-z]+/', $str) || !preg_match('/[A-Z]+/', $str)) {
                $str = substr($str, 0, -1);
                $i = $i - 1;
            }
        }
        if ($type == 7 && strlen($str) >= 3) {
            if (!preg_match('/\d+/', $str) || !preg_match('/[a-z]+/', $str) || !preg_match('/[A-Z]+/', $str)) {
                $str = substr($str, 0, -2);
                $i = $i - 2;
            }
        }
    }
    if (app()->make($table)->where($field, $str)->find()) {
        return random($table,$field, $type ,$length);
    }
    return $str;
}