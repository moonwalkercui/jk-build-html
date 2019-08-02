<?php
/**
 * 静态页面生成控制器类
 * @since  2019-8
 * @author 冷风崔 <541720500@qq.com>
 *
 * 1，config.php中加入以下参数：
 * 'dist_path' => ROOT_PATH. 'public/', // 静态站放置路径
 * 'dist_dir_name' => 'dist', // 静态页的文件夹名 须放置在public下
 * 'dist_module_name' => 'dist', // 静态页的模块名
 * 'dist_file_dot' => '_', // 静态页文件名字中的参数分隔符
 *
 * 2，拷贝dist_rules.php到application目录
 * 3, 有需要在原生tp预览模板并生成静态需求的，可以封装控制器的 fetch 方法
 *
 */
namespace moonwalkercui\JKBuildHtml;

use think\App;

class Utils
{
    // 检查tp版本
    public static function checkTpVer()
    {
        $version = 0;
        if(class_exists(\think\App::class) == false)
        {
            self::handleException("未发现THINKPHP框架或版本符合要求");
        }
        if (defined('THINK_VERSION')) {
            $version = floatval(THINK_VERSION); // 5
        }
        if (defined('\think\App::VERSION')) {
            $version = floatval(\think\App::VERSION); // 5.1
        }
        if ($version != 5 && $version != 5.1) {
            self::handleException("JKBuildHtml暂只支持THINKPHP 5.0和5.1版本");
        }
        return $version;
    }
    // 异常
    public static function handleException($msg)
    {
        throw new \Exception($msg);
    }
}
