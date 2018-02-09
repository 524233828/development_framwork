# 接口项目开发模板

## 项目框架

- **FastD**
- **Version:** v3.1.7
- [FastD相关文档](https://github.com/fastdlabs/fastD/tree/3.1/docs/zh_CN)


## 说明

本项目在fastd v3.1.7的基础上进行扩展，增加了部分内容，并制订了相应开发规范

## 增加内容

### 命令

- Console/ApiDocConsole

该类通过控制器的注释生成相应接口的文档

`bin/console ant-fd:gen-doc -c [控制器名] -o [生成文档的文件夹] -f [生成的文件名]`

### 常量定义

- Constant/CacheKey

redis缓存的key名

- Constant/ErrorCode

常用错误码，更多说明查看`src/Constant/ErrorCode.php`

### 控制器

- Controller/BaseController

所有控制器类必须继承BaseController，该类的response函数实现了通过控制器方法的注释来对出参进行控制。
详情请看`src/Controller/BaseController`的注释，实现示例请看`src/Controller/DemoController.php`

### 异常

- Exception

所有的异常都将通过`src/Exception`中的类进行处理，BaseException实现了几个系统常用异常

### 助手类

- Helper
目前在助手类中有两个类，一个是用于控制器注释解析的，一个是用于过滤的

### 逻辑层

- Logic/BaseLogic

所有逻辑类都必须继承BaseLogic，该类实现了单例模式

### 中间件

- Middleware/Dispatch

该类对路由进行统一的分发，并且集中捕获所有错误异常

- Middleware/IncomeFilter

该类通过解析控制器中的注释，对入参进行过滤，不合法的入参将会失效

### 模型层

- Model

模型层只做一些简单的数据库查询，不进行逻辑踔厉

### 服务器

- Server


### 服务

- Service/ApiResponse

该类实现了统一格式的返回数据，并且设置了跨域请求

- Service/RedisService

该类实现了redis操作

### 服务提供商

- ServiceProvider/EnvConfigServiceProvider

该类实现了配置项扩展，易于分环境配置

- ServiceProvider/RedisServiceProvider

该类将RedisService类加入到容器中

### 常用函数 helper.php

- redis()

获取容器中的redis对象

- is_dev()

判断当前环境是否开发环境

- myLog()

获取monolog对象，更多信息查看函数注释


## 开发流程

- 根据需求确定路由及控制器，编写控制器的注释
- 生成接口文档
- 根据文档开发
- 根据文档编写单元测试


