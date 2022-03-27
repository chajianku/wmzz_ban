**本README.md由 [@Weltolk](https://github.com/Weltolk) 编写**

**2022.03.15 [@Weltolk](https://github.com/Weltolk) 更新**

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

**Updated on 2022.03.15**
