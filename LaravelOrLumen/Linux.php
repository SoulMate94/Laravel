<?php

======Menu

/：根目录，一般根目录下只存放目录，不要存放文件，/etc、/bin、/dev、/lib、/sbin应该和根目录放置在一个分区中

/bin:/usr/bin:可执行二进制文件的目录，如常用的命令ls、tar、mv、cat等。

/boot：放置linux系统启动时用到的一些文件。/boot/vmlinuz为linux的内核文件，以及/boot/gurb。建议单独分区，分区大小100M即可

/dev：存放linux系统下的设备文件，访问该目录下某个文件，相当于访问某个设备，常用的是挂载光驱mount /dev/cdrom /mnt。

/etc：系统配置文件存放的目录，不建议在此目录下存放可执行文件，重要的配置文件有/etc/inittab、/etc/fstab、/etc/init.d、/etc/X11、/etc/sysconfig、/etc/xinetd.d修改配置文件之前记得备份。

注：/etc/X11存放与x windows有关的设置。

/home：系统默认的用户家目录，新增用户账号时，用户的家目录都存放在此目录下，~表示当前用户的家目录，~test表示用户test的家目录。建议单独分区，并设置较大的磁盘空间，方便用户存放数据

/lib:/usr/lib:/usr/local/lib：系统使用的函数库的目录，程序在执行过程中，需要调用一些额外的参数时需要函数库的协助，比较重要的目录为/lib/modules。

/lost+fount：系统异常产生错误时，会将一些遗失的片段放置于此目录下，通常这个目录会自动出现在装置目录下。如加载硬盘于/disk 中，此目录下就会自动产生目录/disk/lost+found

/mnt:/media：光盘默认挂载点，通常光盘挂载于/mnt/cdrom下，也不一定，可以选择任意位置进行挂载。

/opt：给主机额外安装软件所摆放的目录。如：FC4使用的Fedora 社群开发软件，如果想要自行安装新的KDE 桌面软件，可以将该软件安装在该目录下。以前的 Linux 系统中，习惯放置在 /usr/local 目录下

/proc：此目录的数据都在内存中，如系统核心，外部设备，网络状态，由于数据都存放于内存中，所以不占用磁盘空间，比较重要的目录有/proc/cpuinfo、/proc/interrupts、/proc/dma、/proc/ioports、/proc/net/ *等

/root：超级系统管理员root的家目录，系统第一个启动的分区为/，所以最好将/root和/放置在一个分区下。


/sbin:/usr/sbin:/usr/local/sbin：放置系统管理员使用的可执行命令，如fdisk、shutdown、mount等。与/bin不同的是，这几个目录是给系统管理员root使用的命令，一般用户只能"查看"而不能设置和使用。


/tmp：一般用户或正在执行的程序临时存放文件的目录,任何人都可以访问,重要数据不可放置在此目录下


/srv：服务启动之后需要访问的数据目录，如www服务需要访问的网页数据存放在/srv/www内


/usr：应用程序存放目录，/usr/bin存放应用程序，/usr/share存放共享数据，/usr/lib存放不能直接运行的，却是许多程序运行所必需的一些函数库文件。/usr/local:一般存放软件安装的位置。/usr/share/doc:系统说明文件存放目录。/usr/share/man: 程序说明文件存放目录，使用 man ls时会查询/usr/share/man/man1/ls.1.gz的内容建议单独分区，设置较大的磁盘空间


/var：放置系统执行过程中经常变化的文件，如随时更改的日志文件/var/log，/var/log/message：所有的登录文件存放目录，/var/spool/mail：邮件存放的目录，/var/run:程序或服务启动后，其PID存放在该目录下。建议单独分区，设置较大的磁盘空间




==============================Shell===========================================
cd : 切换目录,change directory
cd  ../   : 回到当前目录的上一级目录
cd  ~    : 切换到当前登录用户的家目录
cd  /etc  : 切换到根目录的etc目录下面
pwd : 查看当前所在的目录
ls : 查看文件和目录的信息（list）
选项
-a:显示所有的文件,包括隐藏文件   如:  ls  -a  /etc
-l:列出文件的具体信息                如:  ls  -l   /
-h:列出更人性化的文件的信息         如:   ls -lh     /
whoami : 查看当前终端登录的用户名
whereis : 查看命令所在的位置,也就是命令的二进制文件位置
clear : 清屏命令,快捷键ctrl+l
halt : 关机命令,poweroff ,shutdown -h 0
reboot : 重启命令
logout : 登出远程链接工具的终端
history : 查看历史输入过的命令
man : 命令帮助, 使用格式: man 后面跟着一个命令即可 如: man ls
su : 切换用户
su wei:切换到普通wei用户(root用户切换到普通用户不需要密码)
su root(su -):(如果是普通用户切换到root用户需要密码)
uname -a: 查看linux系统的信息(cat /etc/issue)
touch 1.txt  2.txt:创建文件，多个使用空格隔开
cat 查看文件的内容
关机：
    halt、poweroff
    shutdown -h 0\now :立刻关机
    shutdown -h 10:十分钟后关机
    shutdown -r 10:十分钟后重启
    shutdown -k 10:十分钟后提示关机，但只是警告作用，并不关机
重启：
    reboot
终止一个命令的执行：ctrl+c


=======================VIM====================================================
命令模式(command mode) ：在终端输入vi或vim所进入默认模式，只能输入指令，不能输入编辑文字,在此模式下可以对文件内容进行删除、复制等操作,输入字母i（光标前）或a（光标后）或r（替换）进入编辑模式
    x:删除当前光标所在的字符
    nx:删除当前行包含光标后n个字符
    D:删除当前行光标后的所有的字符
    dd:删除当前光标所在的行
    ndd:删除当前行(包括当前行)后面的n行
    yy:复制当前行
    p:粘贴
    u:撤销

编辑模式(input mode) ：按字母 i 就会进入编辑模式，此时才可以输入文字，编辑代码，按 Esc 回到命令模式。

末行模式(last line mode) ：有一个冒号”:”在那，等待输入命令,在此模式下可以实现文件的保存、退出等功能.。
    :set nu 显示行号
    :set nonu 取消行号
    :n 将光标定位到第n行
    :$ 回到文件的最末行
    :/string 把string字符串进行高亮显示
    :nohls  取消高亮显示
    :w 保存
    :q 退出
    :wq 保存并退出或输入 :x也行
    :wq!    强制保存并退出
    :q! 强制退出
    :[range]s/源字符串/目标字符串/[pattern] 替换字符串

===================RPM========================================================
rpm指令常用的指令如下：
rpm -qa ：查询linux系统已经安装的rpm包
rpm -qa |grep vim：查询系统中安装含有vim名称的rpm包
rpm -e rpm包名(不需要完整) --nodeps ：卸载rpm包(--nodeps 代表强制删除，解决依赖关系)
rpm -ivh rpm包名(完整包名)：安装rpm包
rpm -ql rpm包名(完整的包名)：查询对应rpm软件包所安装的位置信息
注意：|grep（管道符） 相当于mysql 的like的模糊查询


=======================FILE OR DIR=============================================
创建目录（mkdir）和删除(rm)目录的命令
    mkdir oa :创建一个oa目录
    mkdir -p Back/Model:递归创建目录
    rmdir oa:删除空目录
    rmdir -p Back/Model:递归创建空目录
    rm -rf 目录/删除
    -r:代表删除目录或文件，如果是一个目录，其下面的子目录和文件都会递归删除
    -f:强制删除，删除不提示

复制（cp）和重命名-移动(mv)的命令
cp -pR 1.txt  11.txt :复制当前目录的1.txt 到当前目录并重命名为11.txt
cp -pR 1.txt  /home/wei/ :复制当前目录饿1.txt到/home/wei/目录下面
cp -pR 1.txt  /home/wei/ 111.txt:复制当前目录饿1.txt到/home/wei/目录下面,并重命名为111.txt

    cp命令常用选项：
        -p:保留复制文件的原有属性
        -R:递归复制

mv  2.txt  222.txt :把当前目录的2.txt重命名为222.txt
mv  2.txt  /home/wei :把当前目录的2.txt移到/home/wei/目录下面
mv  2.txt  /home/wei /2222.txt:把当前目录的2.txt移到/home/wei/目录下面,并改名为2222.txt


=====================2、权限的查看（ls -l或ll）===============================
第1列：分为10列
以1.php分析如下:
1：文件的类型。-代表普通文件， d代表目录 ，l代表软连接（快捷方式）
rw-r--r--
2-4：rw- ，6=4+2+0 ，代表此文件的拥有者的权限，-符号说明没有权限
5-7：r--,4=4+0+0，代表此文件的所属组的权限
8-10：r--,4=4+0+0，代表此文件的组外权限（其他非组内用户）
那么1.php的文件就是644

此oa目录的权限是 rwxr-xr-x =755
第2列：文件见的node节点数
第3列：文件的拥有者
第4列：文件的所属组
第5列：文件的大小，默认是字节byte,可以使用ls -lh人性化显示大小k
第6列：文件内容的最后修改时间
第7列：文件的名称


===================文件的权限、拥有者、所属组的修改============================
修改文件的权限（chmod）
方式一：通过权限的数字设置权限（重点掌握数字的形式）
    chmod 777 index.php：设置index.php文件三者权限为777,777是最高权限，可读可写可执行。
    chmod 750 index.php:设置index.php文件权限为750,0代表没有权限。
    chmod  -R 644 dir/：设置dir目录及其子目录中所有的文件的权限为644。
    说明：-R 代表递归更改

方式二：通过匹配模式设置权限
    匹配模式有：+、u、g、o。说明：+表示三者，u（user）表示拥有者，g(group)所属组，o(other)组外。
    chmod +x index.php：给文件的拥有者、所属组、组外的权限设置一个x可执行的权限。
    chmod u+x,g+x index.php：给文件的拥有者和所属组设置一个可执行权限。


======================修改文件的拥有者（chown）===============================
chown  wei  index.php：修改index.php文件的拥有者为用户wei
chown  wei.wei  index.php：修改index.php文件的拥有者和所属组都为wei



======================修改文件的所属组（chgrp）===============================
chgrp  root  index.php：修改index.php文件的所属组为root组


======================进程和端口的指令=========================================
①查看进程
ps -A
ps -A |grep 进程名  ：查询某个进程名
或者
ps -aux


②杀死一个进程
kill -9  pid（进程pid）
(注意：-9代表信号量 ，杀的半死不活)
pkill  进程名
(注意：pkill 一定杀死)
killall 进程名（需要完整的进程名称）

pkill -kill -t pts/3

端口的命令
    apache-80 （http-80，https-443）
    mysql-3306
    sshd-22
    ftpd-21

查看系统的端口指令netstat
netstat -tpln | grep ssh

netstat -tpln | grep 22

netstat -natup | grep 22
