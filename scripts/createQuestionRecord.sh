#!/bash/bin
phpCommand=/usr/bin/php
spIndexPath=/home/saber/webroot/study-palace/console/index.php
spPath=/home/saber/webroot/study-palace
spCommand=main/index

${phpCommand} ${spIndexPath} ${spCommand} 语文 1 chinese >${spPath}/logs/chinese 2>&1 &
${phpCommand} ${spIndexPath} ${spCommand} 数学 2 math >${spPath}/logs/math 2>&1 &
${phpCommand} ${spIndexPath} ${spCommand} 英语 3 english >${spPath}/logs/englich 2>&1 &
${phpCommand} ${spIndexPath} ${spCommand} 物理 4 physical >${spPath}/logs/physical 2>&1 &
${phpCommand} ${spIndexPath} ${spCommand} 化学 5 chemistry >${spPath}/logs/chemistry 2>&1 &
${phpCommand} ${spIndexPath} ${spCommand} 生物 6 biological >${spPath}/logs/biological 2>&1 &
${phpCommand} ${spIndexPath} ${spCommand} 历史 7 political >${spPath}/logs/history 2>&1 &
${phpCommand} ${spIndexPath} ${spCommand} 政治 8 history >${spPath}/logs/political 2>&1 &
${phpCommand} ${spIndexPath} ${spCommand} 地理 9 geography >${spPath}/logs/geography 2>&1 &
${phpCommand} ${spIndexPath} ${spCommand} 通用技术 10 common_technology >${spPath}/logs/common_technology 2>&1 &
${phpCommand} ${spIndexPath} ${spCommand} 信息技术 0 internet_technology >${spPath}/logs/internet_technology 2>&1 &