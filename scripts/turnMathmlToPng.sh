#!/bash/bin
phpCommand=/usr/bin/php
spIndexPath=/home/saber/webroot/study-palace/console/index.php
spPath=/home/saber/webroot/study-palace
spCommand=main/turn-mathml-to-png

#/usr/bin/php /home/saber/webroot/study-palace/console/index.php main/turn-mathml-to-png 数学
${phpCommand} ${spIndexPath} ${spCommand} 语文 >/dev/null 2>&1 &
${phpCommand} ${spIndexPath} ${spCommand} 数学 >/dev/null 2>&1 &
${phpCommand} ${spIndexPath} ${spCommand} 英语 >/dev/null 2>&1 &
${phpCommand} ${spIndexPath} ${spCommand} 物理 >/dev/null 2>&1 &
${phpCommand} ${spIndexPath} ${spCommand} 化学 >/dev/null 2>&1 &
${phpCommand} ${spIndexPath} ${spCommand} 生物 >/dev/null 2>&1 &
${phpCommand} ${spIndexPath} ${spCommand} 历史 >/dev/null 2>&1 &
${phpCommand} ${spIndexPath} ${spCommand} 政治 >/dev/null 2>&1 &
${phpCommand} ${spIndexPath} ${spCommand} 地理 >/dev/null 2>&1 &