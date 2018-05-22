<?php
return array(
    'SHOW_PAGE_TRACE'=>true,
	//'配置项'=>'配置值'
    'TMPL_PARSE_STRING'  =>array( //
    '__PUBLIC__' => SITE_URL.'/App/Admin/Public', // 更改默认的/Public 替换规则
    '__INDEX__' => SITE_URL.'/Admin/Index',
    ),

);