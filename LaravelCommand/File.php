<?php

File::exists('path');
File::get('path');
File::getRemote('path');

// 获取文件内容
File::getRequire('path');

// 获取文件内容, 仅能引入一次
File::requireOnce('path');

// 将内容写入文件
File::put('path', 'contents');

// 将内容添加在文件原内容后
File::append('path', 'data');

// 通过给定的路径来删除文件
File::delete('path');

// 将文件移动到新目录下
File::move('path', 'target');

// Copy a file to a new location
// 将文件复制到新目录下
File::copy('path', 'target');

// 从文件的路径地址提取文件的扩展
File::extension('path');

// 获取文件类型
File::type('path');

// 获取文件大小
File::size('path');

// 获取文件的最后修改时间
File::lastModified('path');

// 判断给定的路径是否是文件目录
File::isDirectory('directory');

// 判断给定的路径是否是可写入的
File::isWritable('path');

// 判断给定的路径是否是文件
File::isFile('file');

// 查找能被匹配到的路径名
File::glob($patterns, $flag);

// Get an array of all files in a directory.
// 获取一个目录下的所有文件, 以数组类型返回
File::files('directory');

// 获取一个目录下的所有文件 (递归).
File::allFiles('directory');

// 获取一个目录内的目录
File::directories('directory');

// 创建一个目录
File::makeDirectory('path',  $mode = 0777, $recursive = false);

// 将文件夹从一个目录复制到另一个目录下
File::copyDirectory('directory', 'destination', $options = null);

// 递归式删除目录
File::deleteDirectory('directory', $preserve = false);

// 清空指定目录的所有文件和文件夹
File::cleanDirectory('directory');