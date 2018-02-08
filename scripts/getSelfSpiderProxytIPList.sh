#!/bash/bin
phpCommand=/usr/bin/php
spIndexPath=/home/saber/webroot/study-palace/console/index.php
spPath=/home/saber/webroot/study-palace
spCommand=main/get-self-spider-proxy-ip-list

#/usr/bin/php /home/saber/webroot/study-palace/console/index.php main/get-self-spider-proxy-ip-list 20171030
count=`ps aux | grep "${phpCommand} ${spIndexPath} ${spCommand} 20171030" | grep -v 'grep' | wc -l`
if [ ${count} -lt 1 ];then
    ${phpCommand} ${spIndexPath} ${spCommand} 20171030 >/dev/null 2>&1 &
fi