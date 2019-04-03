# jk-build-html
build static site, thinkphp class, build html page

# JKBuildHtml 基于ThinkPHP生成静态站点控制器类

完美嫁接[http://www.thinkphp.cn/](THINKPHP)的静态页面生成控制器，可自定义生成规则，支持动态参数，支持参数的范围设置。
是一个在原来开发过程没有变化的情况下搭建静态站的解决方案。
性能方面测试有时间搞一下。
暂时不是composer版本，如果需要的，可以留言。
使用中有其他问题的欢迎留言。

[https://gitee.com/ray2017/jk-build-html] 码云仓库地址

## 特点
* 纯静态：生成的网站是静态htm页面，拷贝文件就是部署站点。
* 方便改造：原来TP开发代码不用变动，原来TP的view文件照常写，以前怎么写模板还怎么写。
* 边开发边生成html：建议封装控制器的fetch方法，边查看静态效果边开发，避免后期重新搭建后页面显示有问题，你可以盯着`public/dist`目录下的静态页面按F5刷新静态页面效果，也可以用原来的tp路径或路由查看模板效果。

## 用法

在任意控制器里放置一下语句即可批量生成全部静态页，你需要做的是把它放到后台的某个地方了。
页面显示是flush逐行显示的，如果想用ajax自行搞一下代码当然也行。
```
controller('common/JKBuildHtml')->buildAll();
```
也可以单个页面生成，一般在列表页的每行数据后面加一个 `生一个页面` 按钮：
```
controller('common/JKBuildHtml')->buildOne($path, ['id' => 5]);
```
需要注意的是单个页面生成的path 一般为控制器和方法名，必须在静态生成规则中声明，否则会提示错误。

也可以封装tp controller 的 fetch方法，这样可以边开发边生成。
```
protected function fetchHtml()
{
    controller('common/JKBuildHtml')->buildFromFetch( $html = $this->fetch(), input('get.') );
    return $html;
}

```

## 注意事项
* 所有静态资源js，css，上传文件等，必须放置在 `dist_dir_name` 配置文件夹下，静态页面会访问这些资源，如果放到这个文件夹外面，除非站点目录不是这个目录，否则访问不到。
* 所有静态规则<键>全用小写
* 静态规则中的<值>的路径原则是，只要能请求到的地址就可以，建议不要使用的TP路由动态参数。
* 请求路径只支持GET请求

## 配置步骤

* `JKBuildHtml.php` 放置在common的controller里 别处也可 注意命名空间
* `config.php` 增加公共配置参数
* `dist_rules.php` 静态生成规则文件，放置在application目录下

## config.php 参数设置

公共配置文件`config.php`中加入以下参数,注意看注释:

```
    // 静态站放置路径：
    'dist_path' => ROOT_PATH. 'public/', 
    // 静态页存放文件夹名 一般放置在public下；静态站点直接指向这个目录即可：
    'dist_dir_name' => 'dist', 
    // 生成的静态页子页的存放目录，即匹配规则中没有@符号的页面的存放目录，注意例中路径中的'dist/site-pages'会进行目录匹配作为替换./或../的依据，所以这个名称在项目文件夹名中最好唯一：
    'dist_sub_dir' => 'site-pages', 
    // 要生成静态页的模块名：
    'dist_module_name' => 'index', 
    // 静态页文件名字中的参数分隔符：
    'dist_file_dot' => '_', 
    // 静态资源路径替换 静态站点根目录下会替换成 `./` 其他会替换成 `../`
    'dist_src_match' => '/public/static/', 
```

## 关于静态资源路径
本来tp的资源是放在public下任何位置的，但是有了静态生成类，那么就得按规则来
以下是建议：
* 首先在public下建一个dist目录（dist的由来是写js项目的时候build的目录名，此处借用；也可改配置）
* 然后把所有前端扔给你的所有静态资源文件 js，css,images放到这个文件夹下。
* 把上传的文件也放dist目录里
* 生成完毕后这个目录下就会生成相关的html页面

## 生成静态页规则文件dist_rules.php说明

 * 注：这个文件不是路由文件，和tp路由不是一回事。
  
键值对说明：
 * <键> 为生成静态页文件名：@代表dist的根目录，@index 代表首页，其他不带@的会生成在dist/site-pages;全用小写
 * <值> 为静态页生成模块的路径（即控制器、方法、参数），生成过程中，会直接请求这个路径。

原TP模板文件a链接路径：
 * 在模板里写a链接路径的时候需要按照键的规则，路径里不需要@符号
 
```
<?php
// +----------------------------------------------------------------------
// | 生成静态页的规则文件
// +----------------------------------------------------------------------
return [
    '@index'        => 'index/index', // 这个是首页 会生成在dist目录下
    '@news'         => 'news/index',
    'news_:id'      => ['news/find', 'article'],  // 这个是带db的，表示要查询article表的id列，循环生成静态页
    'job_:id'       => ['jobs/find', 'func:getjobids'],  // 这个是带自定义方法的，表示要执行getjobis方法返回id为键的二维数组，循环生成静态页
    'job_:id_:code' => ['index/index', 'func:dist/index/test'], // 这个是请求tp的模块/控制器/方法，返回一个二维数组
];
```
### <键>
* 键中带:号的是有动态参数的 会生成在`dist/site-pages`目录下
* 参数命名必须和db里的字段名称一致
* 为防止生成错误不同参数之间需用_分开(可以修改配置)

### <值>
* 值可以是一个“请求路径”，用`控制器/方法`的形式即可，请求时会自动加上自定义模块名
* 值也可以是一个数组，第一个是请求路径，会传参请求；第二个是db的名字，即参数字段所在列的所有值，系统会根据参数批量生成页面：比如'news_:id' => ['news/find', 'article'], 是查询article表里的id列，
* 如果想加入db查询条件，那么就放第三个值里 比如 `id < 100`,这个会传入到db的where条件中需要符合tp查询语法, 就成了`'news_:id' => ['news/find', 'article', ['id' =>  ['<',100]]],` 或  `..."id < 100"]`
* 如果想自定义生成id的函数，可以把第二个参数设置成一个全局的方法，可以放common.php里（函数名不用带`func:`）,或任意一个控制器里 写法：`'func:admin/index/getJobIds'` 或 `'func:getjobids'`
* 若采用func类型的，返回值必须是以参数为键相符的二维数组。如：`['id' => [2,3,4,5]]`
* func类型可以有第三个值，作为func的参数传入

### 请求路径出现异常怎么办

静态生成控制器会直接把异常页面也生成到html文件中，不会停止生成

## 作者
冷风崔 <541720500@qq.com>

## LICENSE
完全遵循 996ICU 协议 完美开源
