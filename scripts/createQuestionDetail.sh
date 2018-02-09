#!/bash/bin
phpCommand=/usr/bin/php
spIndexPath=/home/saber/webroot/study-palace/console/index.php
spPath=/home/saber/webroot/study-palace
spCommand=main/create-question-detail

#/usr/bin/php /home/saber/webroot/study-palace/console/index.php main/create-question-detail
${phpCommand} ${spIndexPath} ${spCommand} 语文 >/dev/null 2>&1 &
${phpCommand} ${spIndexPath} ${spCommand} 数学 >/dev/null 2>&1 &
${phpCommand} ${spIndexPath} ${spCommand} 英语 >/dev/null 2>&1 &
${phpCommand} ${spIndexPath} ${spCommand} 物理 >/dev/null 2>&1 &
${phpCommand} ${spIndexPath} ${spCommand} 化学 >/dev/null 2>&1 &
${phpCommand} ${spIndexPath} ${spCommand} 生物 >/dev/null 2>&1 &
${phpCommand} ${spIndexPath} ${spCommand} 历史 >/dev/null 2>&1 &
${phpCommand} ${spIndexPath} ${spCommand} 政治 >/dev/null 2>&1 &
${phpCommand} ${spIndexPath} ${spCommand} 地理 >/dev/null 2>&1 &
#${phpCommand} ${spIndexPath} ${spCommand} 通用技术 10 common_technology >${spPath}/logs/common_technology 2>&1 &
#${phpCommand} ${spIndexPath} ${spCommand} 信息技术 0 internet_technology >${spPath}/logs/internet_technology 2>&1 &