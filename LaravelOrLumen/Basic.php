<?php

==========================Composer=============================================
# Crate Project
composer create-project laravel/laravel Laravel-demo

composer create-project laravel/lumen  Lumen-demo

# Composer Command
composer install

composer update

composer dump-autoload

composer self-updte

composer require [options] [--][vendor/packages]

==========================Artisan=============================================

# Artisan Command
php artisan make:controller name        // create controller


php artisan make:middleware name       // create middleware


php artisan make:migration create_share_shop_table --create=shareshop
                                        //create  table

php artisan migrate             // make migrate


# Queue Command
php artisan queue:table             // 为队列数据库表创建一个新的迁移

php artisan queue:listen  --queue   // 被监听的队列
                          --delay   // 给执行失败的任务设置延时时间
                          --memory  // 内存限制大小 单位MB 默认为128
                          --timeout // 指定任务运行超时秒数 默认为60
                          --sleep   // 等待检查队列任务的秒数 默认为3
                          --tries   // 任务记录失败重试次数 默认为0

php artisan queue:failed            // 查看所有执行失败的队列任务

php artisan queue:failed-table      // 为执行失败的数据表任务创建一个迁移

php artisan queue:flush             // 清除所有执行失败的队列任务

php artisan queue:forget            // 删除一个执行失败的队列任务

php artisan queue:restart           // 在当前的队列任务执行完毕后, 重启队列的守护进程

php artisan queue:retry id          // 对指定 id 的执行失败的队列任务进行重试(id: 失败队列任务的 ID)

php artisan queue:work  --queue   // 被监听的队列
                        --daemon  // 在后台模式运行
                        --delay   // 给执行失败的任务设置延时时间 (默认为零: 0)
                        --force   // 强制在「维护模式下」运行
                        --memory  // 内存限制大小，单位为 MB (默认为: 128)
                        --sleep   // 当没有任务处于有效状态时, 设置其进入休眠的秒数 (默认为: 3)
                        --tries   // 任务记录失败重试次数 (默认为: 0)

# Route Command
php artisan route:cache             // 生成路由缓存文件来提升路由效率

php artisan route:clear             // 移除路由缓存文件

php artisan route:list              // 显示已注册过的路由

# Schedule Commmand
php artisan schedule:run

# Session Command
php artisan session:table

=======================Config==================================================
Config::get('app.timezone');

//指定默认值
Config::get('app.timezone','PRC');

Config::set('database.default','sqlite');

=======================Pagination=============================================
//自动处理分页逻辑
Model::paginate(20);

Model::where('char', 2)->paginate(20);

// 使用简单模板
Model::where('cars', 2)->simplePaginate(20);

//手动分页
Paginator::make($items, $totalItems, $perPage);

//在页面打印分页导航栏
$variable->links();


=======================Routes=================================================
Route::get('foo', function(){});

Route::get('foo', 'controllerName@function');

Route::controller('foo', 'FooController');

===资源路由
Route::resource('posts','PostsController');

// 资源路由只允许指定动作通过
Route::resource('photo','PhotoController',['only' => ['index','show']]);

Route::resource('photo','PhotoController',['except' => ['update','destory']]);

===触发错误
App::abort(404);

$handler->missing(...) in
ErrorServiceProvider::boot();
throw new NotFoundHttpException;


===路由参数
Route::get('foo/{bar}', function($bar){});
Route::get('foo/{bar?}', function($bar = 'bar'){});

===HTTP请求方式
Route::any('foo', function(){});

Route::post('foo', function(){});

Route::put('foo', function(){});

Route::patch('foo', function(){});

Route::delete('foo', function(){});

// RESTful 资源控制器
Route::resource('foo', 'FooController');

// 为一个路由注册多种请求方式
Route::match(['get','post'], '/', function(){});


===安全路由(TBD)
Route::get('foo', array('https', function(){}));


===路由约束
Route::get('foo/{bar}', function($bar){})->where('bar', '[0-9]+');

Route::get('foo/{bar}/{baz}', function($bar, $baz){})
->where(array('bar' => '[0-9]+', 'baz'=>'[A-Za-z]'))

// 设置一个可跨域使用的模式
Route::pattern('bar', '[0-9]+')


===HTTP中间件
// 为路由指定 Middleware
Route::get('admin/profile', ['middleware' => 'auth',function(){}]);

Route::get('admin/profile', function(){})->middleare('auth');

===命名路由
Route::currentRouteName();
Route::get('foo/bar', array('as' => 'foobar', function(){}));

Route::get('user/profile',[
    'as'   => 'profile',
    'uses' => 'UserController@shoeProfile'
]);

Route::get('user/profile', 'UserController@showProfile')->name('profile');

$url = route('profile');
$redirect = redirect()->route('profile');


===路由前缀
Route::group(['prefix' => 'admin'], function() {
    Route::get('user', function(){
        return 'Matches The "/admin/users" URL';
    })
})

===路由命名空间
// 此路由将会传送到 'Foo\Bar'命名空间
Route::group(array('namespace' => 'Foo\Bar'), function(){})


===子域名路由
// {sub}将在闭包中被忽略
Route::group(array('domain' => '{sub}.example.com'), function(){});

========================Session===============================================
Session::get('key');

// 从会话中读取一个条目
Session::get('key','default');
Session::get('key', function(){
    return 'default';
})

// 获取session_id
Session::getId();

//增加一个会话键值数据
Session::put('key','value');

// 将一个值加入到 session 的数组中
Session::push('foo.bar', 'value');

// 返回session的所有条目
Session::all();

Session::has('key');

Session::forget('key');

Session::flush('key');

Session::regener();

Session::flash('key','value');

Session::reflash();

Session::keep(array('key1', 'key2'));

=========================DB===================================================

===基本使用
DB::connection('connection_name');

// 运行数据库查询语句
$results = DB::select('select * from users where id = ?', [1]);
$results = DB::select('select * from users where id = :id', ['id' => 1]);

//运行普通语句
DB::statement('drop table users');

//监听查询事件
DB::listen(function($sql, $bindings, $time){code_here;});

//数据库事务处理
DB::transaction(function(){
    DB::table('users')->update(['votes' => 1]);
    DB::table('posts')->delete();
});
DB::beginTransaction();
DB::rollback();
DB::commit();

===查询语句构造器
// 取得数据表的所有行
DB::table('name')->get();

//取得数据表的部分数据
DB::table('users')->chunk(100, function($users) {
    foreach ($user as $user) {
        # code...
    }
})

//取得数据表的第一条数据
$user = DB::table('users')->where('name' , 'John')->first();
DB::table('name')->first();

// 从单行中取出单列数据
$name = DB::table('users')->where('name','John')->pluck('name');
DB::table('name')->pluck('column');

// 取多行数据的「列数据」数组
$roles = DB::table('roles')->lists('title');
$roles = DB::table('roles')->lists('title','name');

// 指定一个选择字句
$users = DB::table('users')->select('name','email')->get();
$users = DB::table('users')->distinct()->get();
$users = DB::table('users')->select('name as user_name')->get();

// 添加一个选择字句到一个已存在的查询语句中
$query = DB::table('users')->select('name');
$users = $query->addSelect('age')->get();

// 使用 Where 运算符
$users = DB::table('users')->where('votes', '>', 100)->get();
$users = DB::table('users')
              ->where('votes', '>', 100)
              ->orWhere('name', 'John')
              ->get();

$users = DB::table('users')
      ->whereBetween('votes', [1, 100])->get();

$users = DB::table('users')
      ->whereNotBetween('votes', [1, 100])->get();

$users = DB::table('users')
      ->whereIn('id', [1, 2, 3])->get();

$users = DB::table('users')
      ->whereNotIn('id', [1, 2, 3])->get();

$users = DB::table('users')
      ->whereNull('updated_at')->get();

DB::table('name')->whereNotNull('column')->get();

// 动态的 Where 字句
$admin = DB::table('users')->whereId(1)->first();

$john = DB::table('users')
      ->whereIdAndEmail(2, 'john@doe.com')
      ->first();

$jane = DB::table('users')
      ->whereNameOrAge('Jane', 22)
      ->first();

// Order By, Group By, 和 Having
$users = DB::table('users')
      ->orderBy('name', 'desc')
      ->groupBy('count')
      ->having('count', '>', 100)
      ->get();

DB::table('name')->orderBy('column')->get();

DB::table('name')->orderBy('column','desc')->get();

DB::table('name')->having('count', '>', 100)->get();

// 偏移 & 限制
$users = DB::table('users')->skip(10)->take(5)->get();


Joins
// 基本的 Join 声明语句
DB::table('users')
    ->join('contacts', 'users.id', '=', 'contacts.user_id')
    ->join('orders', 'users.id', '=', 'orders.user_id')
    ->select('users.id', 'contacts.phone', 'orders.price')
    ->get();

// Left Join 声明语句
DB::table('users')
->leftJoin('posts', 'users.id', '=', 'posts.user_id')
->get();

// select * from users where name = 'John' or (votes > 100 and title <> 'Admin')
DB::table('users')
    ->where('name', '=', 'John')
    ->orWhere(function($query)
    {
        $query->where('votes', '>', 100)
              ->where('title', '<>', 'Admin');
    })
    ->get();

===聚合
$users = DB::table('users')->count();
$price = DB::table('orders')->max('price');
$price = DB::table('orders')->min('price');
$price = DB::table('orders')->avg('price');
$total = DB::table('users')->sum('votes');

===原始表达句
$users = DB::table('users')
                    ->select(DB::raw('count(*) as user_count, status'))
                    ->where('status','<>',1)
                    ->group('status')
                    ->get();

// 返回行
DB::select('select * from users where id = ?', array('value'));

DB::insert('insert into foo set bar=2');

DB::update('update foo set bar=2');

DB::delete('delete from bar');

// 返回 void
DB::statement('update foo set bar=2');
// 在声明语句中加入原始的表达式
DB::table('name')->select(DB::raw('count(*) as count, column2'))->get();

Inserts / Updates / Deletes / Unions / Pessimistic Locking
// 插入
DB::table('users')->insert(
  ['email' => 'john@example.com', 'votes' => 0]
);
$id = DB::table('users')->insertGetId(
  ['email' => 'john@example.com', 'votes' => 0]
);
DB::table('users')->insert([
  ['email' => 'taylor@example.com', 'votes' => 0],
  ['email' => 'dayle@example.com', 'votes' => 0]
]);

// 更新
DB::table('users')
          ->where('id', 1)
          ->update(['votes' => 1]);
DB::table('users')->increment('votes');
DB::table('users')->increment('votes', 5);
DB::table('users')->decrement('votes');
DB::table('users')->decrement('votes', 5);
DB::table('users')->increment('votes', 1, ['name' => 'John']);

// 删除
DB::table('users')->where('votes', '<', 100)->delete();
DB::table('users')->delete();
DB::table('users')->truncate();

// 集合
// unionAll() 方法也是可供使用的，调用方式与 union 相似
$first = DB::table('users')->whereNull('first_name');
$users = DB::table('users')->whereNull('last_name')->union($first)->get();

// 消极锁
DB::table('users')->where('votes', '>', 100)->sharedLock()->get();
DB::table('users')->where('votes', '>', 100)->lockForUpdate()->get();



=========================Model================================================
===基础使用
// 定义一个 Eloquent 模型
 class User extends Model {}

// 生成一个 Eloquent 模型
php artisan make:model User

// 指定一个自定义的数据表名称
 class User extends Model {
  protected $table = 'my_users';
}

===More
Model::create(array('key' => 'value'));

// 通过属性找到第一条相匹配的数据或创造一条新数据
 Model::firstOrCreate(array('key' => 'value'));

// 通过属性找到第一条相匹配的数据或实例化一条新数据
 Model::firstOrNew(array('key' => 'value'));

// 通过属性找到相匹配的数据并更新，如果不存在即创建
 Model::updateOrCreate(array('search_key' => 'search_value'), array('key' => 'value'));

// 使用属性的数组来填充一个模型, 用的时候要小心「Mass Assignment」安全问题 !
Model::fill($attributes);
Model::destroy(1);
Model::all();
Model::find(1);

// 使用双主键进行查找
Model::find(array('first', 'last'));

// 查找失败时抛出异常
Model::findOrFail(1);

// 使用双主键进行查找, 失败时抛出异常
Model::findOrFail(array('first', 'last'));
Model::where('foo', '=', 'bar')->get();
Model::where('foo', '=', 'bar')->first();
Model::where('foo', '=', 'bar')->exists();

// 动态属性查找
 Model::whereFoo('bar')->first();

// 查找失败时抛出异常
Model::where('foo', '=', 'bar')->firstOrFail();
Model::where('foo', '=', 'bar')->count();
Model::where('foo', '=', 'bar')->delete();

// 输出原始的查询语句
Model::where('foo', '=', 'bar')->toSql();
Model::whereRaw('foo = bar and cars = 2', array(20))->get();
Model::on('connection-name')->find(1);
Model::with('relation')->get();
Model::all()->take(10);
Model::all()->skip(10);

// 默认的 Eloquent 排序是上升排序
Model::all()->orderBy('column');
Model::all()->orderBy('column','desc');

===软删除
Model::withTrashed()->where('cars', 2)->get();

// 在查询结果中包括 被软删除的模型
Model::withTrashed()->where('cars', 2)->restore();
Model::where('cars', 2)->forceDelete();

// 查找只带有软删除的模型
Model::onlyTrashed()->where('cars', 2)->get();

===模型关联
// 一对一 - User::phone()
 return $this->hasOne('App\Phone', 'foreign_key', 'local_key');

// 一对一 - Phone::user(), 定义相对的关联
 return $this->belongsTo('App\User', 'foreign_key', 'other_key');

// 一对多 - Post::comments()
 return $this->hasMany('App\Comment', 'foreign_key', 'local_key');

//  一对多 - Comment::post()
 return $this->belongsTo('App\Post', 'foreign_key', 'other_key');

// 多对多 - User::roles();
 return $this->belongsToMany('App\Role', 'user_roles', 'user_id', 'role_id');

// 多对多 - Role::users();
 return $this->belongsToMany('App\User');

// 多对多 - Retrieving Intermediate Table Columns
$role->pivot->created_at;

// 多对多 - 中介表字段
 return $this->belongsToMany('App\Role')->withPivot('column1', 'column2');

// 多对多 - 自动维护 created_at 和 updated_at 时间戳
 return $this->belongsToMany('App\Role')->withTimestamps();

// 远层一对多 - Country::posts(), 一个 Country 模型可能通过中介的 Users
// 模型关联到多个 Posts 模型(User::country_id)
return $this->hasManyThrough('App\Post', 'App\User', 'country_id', 'user_id');

// 多态关联 - Photo::imageable()
return $this->morphTo();

// 多态关联 - Staff::photos()
 return $this->morphMany('App\Photo', 'imageable');

// 多态关联 - Product::photos()
 return $this->morphMany('App\Photo', 'imageable');

// 多态关联 - 在 AppServiceProvider 中注册你的「多态对照表」
 Relation::morphMap([
    'Post' => App\Post::class,
    'Comment' => App\Comment::class,
]);

// 多态多对多关联 - 涉及数据库表: posts,videos,tags,taggables
 // Post::tags()
 return $this->morphToMany('App\Tag', 'taggable');
// Video::tags()
 return $this->morphToMany('App\Tag', 'taggable');
// Tag::posts()
 return $this->morphedByMany('App\Post', 'taggable');
// Tag::videos()
 return $this->morphedByMany('App\Video', 'taggable');

// 查找关联
$user->posts()->where('active', 1)->get();
// 获取所有至少有一篇评论的文章...
$posts = App\Post::has('comments')->get();
// 获取所有至少有三篇评论的文章...
$posts = Post::has('comments', '>=', 3)->get();
// 获取所有至少有一篇评论被评分的文章...
$posts = Post::has('comments.votes')->get();
// 获取所有至少有一篇评论相似于 foo% 的文章
$posts = Post::whereHas('comments', function ($query) {
    $query->where('content', 'like', 'foo%');
})->get();

// 预加载
$books = App\Book::with('author')->get();
$books = App\Book::with('author', 'publisher')->get();
$books = App\Book::with('author.contacts')->get();

// 延迟预加载
$books->load('author', 'publisher');

// 写入关联模型
$comment = new App\Comment(['message' => 'A new comment.']);
$post->comments()->save($comment);

// Save 与多对多关联
$post->comments()->saveMany([
    new App\Comment(['message' => 'A new comment.']),
    new App\Comment(['message' => 'Another comment.']),
]);
$post->comments()->create(['message' => 'A new comment.']);

// 更新「从属」关联
$user->account()->associate($account);
$user->save();
$user->account()->dissociate();
$user->save();

// 附加多对多关系
$user->roles()->attach($roleId);
$user->roles()->attach($roleId, ['expires' => $expires]);

// 从用户上移除单一身份...
$user->roles()->detach($roleId);

// 从用户上移除所有身份...
$user->roles()->detach();
$user->roles()->detach([1, 2, 3]);
$user->roles()->attach([1 => ['expires' => $expires], 2, 3]);

// 任何不在给定数组中的 IDs 将会从中介表中被删除。
$user->roles()->sync([1, 2, 3]);

// 你也可以传递中介表上该 IDs 额外的值：
$user->roles()->sync([1 => ['expires' => true], 2, 3]);

===Eloquent 配置信息
// 关闭模型插入或更新操作引发的 「mass assignment」异常
 Eloquent::unguard();

// 重新开启「mass assignment」异常抛出功能
 Eloquent::reguard();

=========================Schema===============================================
// 创建指定数据表
Schema::create('table', function($table)
{
  $table->increments('id');
});

// 指定一个连接
 Schema::connection('foo')->create('table', function($table){});

// 通过给定的名称来重命名数据表
 Schema::rename($from, $to);

 // 移除指定数据表
 Schema::drop('table');

// 当数据表存在时, 将指定数据表移除
 Schema::dropIfExists('table');

// 判断数据表是否存在
 Schema::hasTable('table');

// 判断数据表是否有该列
 Schema::hasColumn('table', 'column');

// 更新一个已存在的数据表
 Schema::table('table', function($table){});

// 重命名数据表的列
$table->renameColumn('from', 'to');

// 移除指定的数据表列
$table->dropColumn(string|array);

// 指定数据表使用的存储引擎
$table->engine = 'InnoDB';

// 字段顺序，只能在 MySQL 中才能用
$table->string('name')->after('email');


===索引
$table->string('column')->unique();
$table->primary('column');

// 创建一个双主键
$table->primary(array('first', 'last'));
$table->unique('column');
$table->unique('column', 'key_name');

// 创建一个双唯一性索引
$table->unique(array('first', 'last'));
$table->unique(array('first', 'last'), 'key_name');
$table->index('column');
$table->index('column', 'key_name');

// 创建一个双索引
$table->index(array('first', 'last'));
$table->index(array('first', 'last'), 'key_name');

$table->dropPrimary(array('column'));
$table->dropPrimary('table_column_primary');
$table->dropUnique(array('column'));
$table->dropUnique('table_column_unique');
$table->dropIndex(array('column'));
$table->dropIndex('table_column_index');


===外键
$table->foreign('user_id')->references('id')->on('users');

$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'|'restrict'|'set null'|'no action')

$table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade'|'restrict'|'set null'|'no action')

$table->dropForeign(array('user_id'));

$table->dropForeign('posts_user_id_foreign');

===字段类型
// 自增
$table->increments('id');
$table->bigIncrements('id');

// 数字
$table->integer('votes');
$table->tinyInteger('votes');
$table->smallInteger('votes');
$table->mediumInteger('votes');
$table->bigInteger('votes');
$table->float('amount');
$table->double('column', 15, 8);
$table->decimal('amount', 5, 2);


// 字符串和文本
$table->char('name', 4);
$table->string('email');
$table->string('name', 100);
$table->text('description');
$table->mediumText('description');
$table->longText('description');

// 日期和时间
$table->date('created_at');
$table->dateTime('created_at');
$table->time('sunrise');
$table->timestamp('added_on');
// Adds created_at and updated_at columns
// 添加 created_at 和 updated_at 行
$table->timestamps();
$table->nullableTimestamps();


// 其它类型
$table->binary('data'); //二进制
$table->boolean('confirmed'); //Bool

// 为软删除添加 deleted_at 字段
$table->softDeletes();
$table->enum('choices', array('foo', 'bar')); //枚举

// 添加 remember_token 为 VARCHAR(100) NULL
$table->rememberToken();

// 添加整型的 parent_id 和字符串类型的 parent_type
$table->morphs('parent');
->nullable()
->default($value)
->unsigned()

=========================Cache================================================
Cache::put('Key', 'value', $minutes);
Cache::add('Key', 'value', $minutes);

Cache::forever('key','value');

Cache::remember('key',$minutes, function(){ return 'value'});

Cache::rememberForever('key', function(){ return 'value' });

Cache::forget('key');

Cache::has('key');

Cache::get('key');
Cache::get('key', 'default');
Cache::get('key', function(){ return 'default'; });

Cache::tags('my-tag')->put('key','value', $minutes);
Cache::tags('my-tag')->has('key');
Cache::tags('my-tag')->get('key');
Cache::tags('my-tag')->forget('key');
Cache::tags('my-tag')->flush();

Cache::increment('key');
Cache::increment('key', $amount);
Cache::decrement('key');
Cache::decrement('key', $amount);


Cache::section('group')->put('key', $value);
Cache::section('group')->get('key');
Cache::section('group')->flush();


=========================Request==============================================
// url: http://xx.com/aa/bb
Request::url();

// 路径: /aa/bb
Requst::path();

// 获取请求 Uri: /aa/bb/?c=d
Request::getRequestUri();

// 返回用户的 IP
Request::ip();

// 获取 Uri: http://xx.com/aa/bb/?c=d
Request::getUri();

// 获取查询字符串 c=d
Request::getQueryString();

// 获取请求端口 (例如 80, 443 等等)
Request::getPort();

// 判断当前请求的 URI 是否可被匹配
Request::is('foo/*');

// 获取 URI 的分段值 (索引从 1 开始)
Request::segment(1);

// 从请求中取回头部信息
Request::header('Content-Type');

// 从请求中取回服务器变量
Request::server('PATH_INFO');

// 判断请求是否是 AJAX 请求
Request::ajax();

// 判断请求是否使用 HTTPS
Request::secure();

// 获取请求方法
Request::method();

// 判断请求方法是否是指定类型的
Request::isMethod('post');

// 获取原始的 POST 数据
Request::instance()->getContent();

// 获取请求要求返回的格式
Request::format();

// 判断 HTTP Content-Type 头部信息是否包含 */json
Request::isJson();

// 判断 HTTP Accept 头部信息是否为 application/json
Request::wantsJson();

=========================Response=============================================
return Response::make($contents);

return Response::make($contents, 200);

return Response::json(array('key' => 'value'));

return Response::json(array('key' => 'value'));
                      ->setCallback(Input::get('callback'));

return Response::download($filepath);

return Response::download($filepath, $filename, $headers);

// 创建一个回应且修改头部信息的值
$response = Response::make($contents, 200);

$response->header('Content-Type', 'application/json');

return $response;

//为回应附加上 cookie
return Response::make($content)
->withCookie(Cookie::make('key', 'value'));

=========================Helper===============================================
===数组
// 如果给定的键不存在于该数组，array_add 函数将给定的键值对加到数组中
array_add($array, 'key', 'value');

// 将数组的每一个数组折成单一数组
array_collapse($array);

// 函数返回两个数组，一个包含原本数组的键，另一个包含原本数组的值
array_divide($array);

// 把多维数组扁平化成一维数组，并用「点」式语法表示深度
array_dot($array);

// 从数组移除给定的键值对
array_except($array, array('key'));

// 返回数组中第一个通过为真测试的元素
array_first($array, function($key, $value){}, $default);

// 将多维数组扁平化成一维
 // ['Joe', 'PHP', 'Ruby'];
array_flatten(['name' => 'Joe', 'languages' => ['PHP', 'Ruby']]);

// 以「点」式语法从深度嵌套数组移除给定的键值对
array_forget($array, 'foo');
array_forget($array, 'foo.bar');

// 使用「点」式语法从深度嵌套数组取回给定的值
array_get($array, 'foo', 'default');
array_get($array, 'foo.bar', 'default');

// 使用「点」式语法检查给定的项目是否存在于数组中
array_has($array, 'products.desk');

// 从数组返回给定的键值对
array_only($array, array('key'));

// 从数组拉出一列给定的键值对
array_pluck($array, 'key');

// 从数组移除并返回给定的键值对
array_pull($array, 'key');

// 使用「点」式语法在深度嵌套数组中写入值
array_set($array, 'key', 'value');
array_set($array, 'key.subkey', 'value');

// 借由给定闭包结果排序数组
array_sort($array, function(){});

// 使用 sort 函数递归排序数组
array_sort_recursive();

// 使用给定的闭包过滤数组
array_where();

// 返回给定数组的第一个元素
head($array);

// 返回给定数组的最后一个元素
 last($array);



 ===路径
// 取得 app 文件夹的完整路径
app_path();   // Lumen 报错 以下均是Lumen框架下

// 取得项目根目录的完整路径
base_path();  // "D:\PHPStudy\PHPTutorial\WWW\hcm_proxy"

// 取得应用配置目录的完整路径
config_path();  // null

// 取得应用数据库目录的完整路径
database_path();  // "D:\PHPStudy\PHPTutorial\WWW\hcm_proxy\database"

// 取得加上版本号的 Elixir 文件路径
elixir(); // null

// 取得 public 目录的完整路径
public_path();  // null

// 取得 storage 目录的完整路径
storage_path();  // "D:\PHPStudy\PHPTutorial\WWW\hcm_proxy/storage" 

===字符串
// 将给定的字符串转换成 驼峰式命名
camel_case($value);

// 返回不包含命名空间的类名称
class_basename($class);
class_basename($object);

// 对给定字符串运行 htmlentities
e('<html>');

// 判断字符串开头是否为给定内容
starts_with('Foo bar.', 'Foo');

// 判断给定字符串结尾是否为指定内容
ends_with('Foo bar.', 'bar.');

// 将给定的字符串转换成 蛇形命名
snake_case('fooBar');

// 限制字符串的字符数量
str_limit();

// 判断给定字符串是否包含指定内容
str_contains('Hello foo bar.', 'foo');

// 添加给定内容到字符串结尾，foo/bar/
str_finish('foo/bar', '/');

// 判断给定的字符串与给定的格式是否符合
str_is('foo*', 'foobar');

// 转换字符串成复数形
str_plural('car');

// 产生给定长度的随机字符串
str_random(25);

// 转换字符串成单数形。该函数目前仅支持英文
str_singular('cars');

// 从给定字符串产生网址友善的「slug」
str_slug("Laravel 5 Framework", "-");

// 将给定字符串转换成「首字大写命名」: FooBar
studly_case('foo_bar');

// 根据你的本地化文件翻译给定的语句
trans('foo.bar');

// 根据后缀变化翻译给定的语句
trans_choice('foo.bar', $count);



===URLs and Links
// 产生给定控制器行为网址
action('FooController@method', $parameters);

// 根据目前请求的协定（HTTP 或 HTTPS）产生资源文件网址
asset('img/photo.jpg', $title, $attributes);

// 根据 HTTPS 产生资源文件网址
secure_asset('img/photo.jpg', $title, $attributes);

// 产生给定路由名称网址
route($route, $parameters, $absolute = true);

// 产生给定路径的完整网址
url('path', $parameters = array(), $secure = null);

===Miscellaneous  其他
// 返回一个认证器实例。你可以使用它取代 Auth facade
auth()->user();

// 产生一个重定向回应让用户回到之前的位置
back();

// 使用 Bcrypt 哈希给定的数值。你可以使用它替代 Hash facade
bcrypt('my-secret-password');

// 从给定的项目产生集合实例
collect(['taylor', 'abigail']);

// 取得设置选项的设置值
config('app.timezone', $default);

// 产生包含 CSRF 令牌内容的 HTML 表单隐藏字段
{!! csrf_field() !!}

// 取得当前 CSRF 令牌的内容
$token = csrf_token();

// 输出给定变量并结束脚本运行
dd($value);

// 取得环境变量值或返回默认值
$env = env('APP_ENV');
$env = env('APP_ENV', 'production');

// 配送给定事件到所属的侦听器
event(new UserRegistered($user));

// 根据给定类、名称以及总数产生模型工厂建构器
$user = factory(App\User::class)->make();

// 产生拟造 HTTP 表单动作内容的 HTML 表单隐藏字段
{!! method_field('delete') !!}


// 取得快闪到 session 的旧有输入数值
$value = old('value');
$value = old('value', 'default');

// 返回重定向器实例以进行 重定向
return redirect('/home');

// 取得目前的请求实例或输入的项目
$value = request('key', $default = null);

// 创建一个回应实例或获取一个回应工厂实例
return response('Hello World', 200, $headers);

// 可被用于取得或设置单一 session 内容
$value = session('key');

// 在没有传递参数时，将返回 session 实例
$value = session()->get('key');
session()->put('key', $value);

// 返回给定数值
value(function(){ return 'bar'; });

// 取得视图 实例
return view('auth.login');

// 返回给定的数值
$value = with(new Foo)->work();