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
  layui.use(['element'], () => {
    const element = layui.element
    let thUrl = location.pathname
    $('dd[fiy-side-tpl]').each((index, elem) => {
        if($(elem).children('a').attr('href') == thUrl){
            $('dd[fiy-side-tpl]').removeClass('layui-this')
            $(elem).addClass('layui-this')
            return false
        }
    })
  })
  
})
$(document).pjax('a[data-pjax]', '#pjax-container', {})



/* LAYUI-USE */
layui.use(['layer', 'form', 'element'], () => {
  const layer = layui.layer
  const form = layui.form
  const element = layui.form
  
})