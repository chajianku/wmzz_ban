**当前版本:**

V1.7

**注意事项:**

1. 贴吧添加封禁时需要提供封禁原因,本插件添加封禁用户时填入的封禁原因即为此项

2. 从老版本更新上来前请查看底部的[更新步骤](#更新步骤)

**使用教程:**

本插件封禁用户时使用目标用户的portrait

- 获取用户的portrait的方法:

- - web端点开用户的主页,url里的id参数就是portrait

- 另:

- - 添加封禁时可以直接粘贴用户的portrait(即主页url里的id参数)或直接粘贴用户主页的url(只要粘贴的内容包含"id="和id的参数即可)
- - 比如可以粘贴:

1. tb.1.xxxxxxxx.xxxxxxxxxxxxxxxxxxxxxx
2. https://tieba.baidu.com/home/main?id=tb.1.xxxxxxxx.xxxxxxxxxxxxxxxxxxxxxx&fr=pb
3. https://tieba.baidu.com/home/main?id=tb.1.xxxxxxxx.xxxxxxxxxxxxxxxxxxxxxx

- - 均会自动提取出id参数

***

## 更新步骤

**由2022年09月14日版本[https://github.com/chajianku/wmzz_ban/tree/9dcb9a95380c363332f7df3ae412f55059646e75](https://github.com/chajianku/wmzz_ban/tree/9dcb9a95380c363332f7df3ae412f55059646e75)更新到目前版本的步骤:**

1. 备份云签到平台数据库的"前缀_wmzz_ban"表(默认为"tc_wmzz_ban"表)

2. 备份云签到平台中本插件的设置项(默认封禁原因,默认封禁贴吧,默认封禁id和单次计划任务连续封禁次数)

3. 在云签到平台插件管理页面卸载本插件,然后更新插件文件并安装

4. 插件更新后,与封禁相关的设定都在云签到平台菜单栏的"循环封禁"

**由2017年01月09日版本[https://github.com/chajianku/wmzz_ban/tree/309d3b0ea596f7d0e0b1e857c358ebc292a86afe](https://github.com/chajianku/wmzz_ban/tree/309d3b0ea596f7d0e0b1e857c358ebc292a86afe)更新到目前版本的步骤:**

**因为插件更新后封禁用户所使用的用户参数不同,所以之前表里存储的封禁用户的数据无法使用,需要更新插件然后重新添加封禁用户,而且目前更新后无法兼容旧版插件的数据表,请谨慎升级**

一.本次更新需要删除本插件以前创建并使用的表然后重新创建表

- 上述操作最简单的方法:

1. 更新本插件

- 更新本插件的方法
-
    1. 在网站的插件管理页面,卸载本插件
-
    2. 删除网站源文件里的plugins文件夹下的wmzz_ban文件夹
-
    3. 下载或git clone本项目到网站源文件里的plugins文件夹下
-
    - 如果是下载的项目的压缩包,则解压出来本项目的文件夹然后复制到网站源文件里的plugins文件夹下
-
    - 如果是git clone本项目,则git clone本项目到网站源文件里的plugins文件夹下

- 保证git clone或下载解压之后的目录情况:plugins/wmzz_ban/*.php

2. 在网站的插件管理页面,安装和激活本插件

二.本插件更新之后,封禁用户将只使用用户的portrait

- 获取用户的portrait的方法:

1. web端点开用户的主页
2. url里的id参数就是portrait

- 添加封禁时可以直接粘贴用户的portrait(即主页url里的id参数)或直接粘贴用户主页的url(只要粘贴的内容包含"id="和id参数即可)
- 比如可以粘贴:

1. tb.1.xxxxxxxx.xxxxxxxxxxxxxxxxxxxxxx
2. https://tieba.baidu.com/home/main?id=tb.1.xxxxxxxx.xxxxxxxxxxxxxxxxxxxxxx&fr=pb
3. https://tieba.baidu.com/home/main?id=tb.1.xxxxxxxx.xxxxxxxxxxxxxxxxxxxxxx

- 均会自动提取出id参数
