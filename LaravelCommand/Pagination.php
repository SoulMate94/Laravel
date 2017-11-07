<?php

// 自动处理分页逻辑
Model::paginate(15);
Model::where('cars', 2)->paginate(15);

// 使用简单模板 - 只有 "上一页" 或 "下一页" 链接
Model::where('cars', 2)->simplePaginate(15);

// 手动分页
Paginator::make($items, $totalItems, $perPage);

// 在页面打印分页导航栏
$variable->links();