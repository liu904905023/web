<?php
return array(
	   'TMPL_L_DELIM'      => '<{',// 模板引擎普通标签开始标记
    'TMPL_R_DELIM'      => '}>',// 模板引擎普通标签结束标记
    'DEFAULT_MODULE'    => 'Home',
    'MODULE_ALLOW_LIST' => array( 'Home' ),
    'SERVER_HOST' => 'http://payapi.yunlaohu.cn/',
	'TMPL_CACHE_ON' =>false,
	'TOKEN_ON'      =>    true,  // 是否开启令牌验证 默认关闭
    'TOKEN_NAME'    =>    '__hash__',    // 令牌验证的表单隐藏字段名称，默认为__hash__
    'TOKEN_TYPE'    =>    'md5',  //令牌哈希验证规则 默认为MD5
    'TOKEN_RESET'   =>    true,  //令牌验证出错后是否重置令牌 默认为true
);