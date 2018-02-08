#!/bash/bin
phpCommand=/usr/bin/php
spIndexPath=/home/saber/webroot/study-palace/console/index.php
spPath=/home/saber/webroot/study-palace
spCommand=main/get-self-proxy-ip-list

${phpCommand} ${spIndexPath} ${spCommand}
count=`ps aux | grep "${phpCommand} ${spPath} ${spCommand} 20171030" | grep -v 'grep' | wc -l`
if [ ${count} -lt 1 ];then
    ${phpCommand} ${spPath} ${spCommand} 20171030 >/dev/null 2>&1 &
fi