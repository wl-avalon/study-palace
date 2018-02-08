#!/bash/bin
phpCommand=/usr/local/bin/php
spIndexPath=/Users/wzj-dev/Documents/source_code/study-palace/console/index.php
spPath=/Users/wzj-dev/Documents/source_code/study-palace
spCommand=main/get-self-proxy-ip-list

${phpCommand} ${spIndexPath} ${spCommand}
count=`ps aux | grep "${phpCommand} ${spPath} ${spCommand} 20171030" | grep -v 'grep' | wc -l`
if [ ${count} -lt 1 ];then
    ${phpCommand} ${spPath} ${spCommand} 20171030 >/dev/null 2>&1 &
fi