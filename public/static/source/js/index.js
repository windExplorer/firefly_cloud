/* LAYUI-DEFINE LAYUI-CONFIG */
layui.config({
  dir: '/static/extend/layui/',
  version: false,
  debug: true,
  base: '/static/extend/plugins/'
})



/* PJAX */
$(document).on('pjax:start', () => {
  console.log('PJAX开始')
})
$(document).on('pjax:end', () => {
  console.log('PJAX结束')
})
$(document).pjax('a[data-pjax]', '#pjax-container', {})



