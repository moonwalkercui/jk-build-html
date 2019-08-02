<?php
// +----------------------------------------------------------------------
// | 生成静态页的规则文件 详细见readme
// | @author  冷风崔
// +----------------------------------------------------------------------

return [
    // 这个是首页 带@的会生成在dist目录下,否则生成在子文件夹里；生成的html文件不带@
    '@index'        => 'index/index',
    '@news'         => 'news/index',

    // 这个是带db的，表示要查询article表的id列，循环生成静态页
    'news_:id'      => ['news/find', 'article'],

    // 这个是带自定义方法的，表示要执行getjobis方法返回id为键的二维数组，循环生成静态页
    'job_:id'       => ['jobs/find', 'func:getjobids'],

    // 这个是请求tp的模块/控制器/方法，返回一个二维数组
    'job_:id_:code' => ['index/index', 'func:dist/index/test'],
];