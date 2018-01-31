/**
 * 自动部署，基于 fis3
 * @author yaodongwei
 */
fis.set('namespace', 'rrxframework');
fis.set('module', 'rrxframework');

//忽略这些文件的部署
fis.set('project.ignore', [
  'output/**',
  'script/**',
  'node_modules/**',
  '.git/**',
  '.svn/**',
  'fis-conf.js',
  'deploy.sh',
  'build.sh',
  'README.md'
]);

//默认部署到 modules/$modName 下面
fis.match('*', {
  release: '${module}/$&',
});

fis.media('dev').match('*', {
  deploy: fis.plugin('http-push', {
    receiver: 'http://100.73.16.41:8000/phpfis/fis',
    to: '/data/application' // 注意这个是指的是测试机器的路径，而非本地机器
  })
});