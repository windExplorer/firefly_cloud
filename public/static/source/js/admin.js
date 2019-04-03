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
  
  const Request = (url, data = '', type = 'get') => {
    $.ajax({
      url: url,
      type: type,
      data: data,
      dataType: 'json',
      async: true,
      success: (res) => {
        if(1 == res.code){
          layer.msg(res.msg)
          setTimeout(function(){
            location.reload()
          },1000)
        } else {
          layer.msg(res.msg)
        }
      },
    })
  }

  /**
   * dom操作
    */
  $(document).on('click', 'a[fiy-logout]', function() {
    Request($(this).attr('fiy-link'))
  })

  $(document).on('click', 'a[fiy-shrink]', function() {
    let flag = $(this).attr('fiy-flag')
    if(1 == flag){
      $(this).children('i').removeClass('layui-icon-shrink-right')
      $(this).children('i').addClass('layui-icon-spread-left')
      $('.layui-layout-admin .layui-side').addClass('layui-side2')
      $('.layui-body').addClass('layui-body2')
      $('.layui-footer').addClass('layui-footer2')
      $(this).attr('fiy-flag', 2)
    } else {
      $(this).children('i').removeClass('layui-icon-spread-left')
      $(this).children('i').addClass('layui-icon-shrink-right')
      $('.layui-layout-admin .layui-side').removeClass('layui-side2')
      $('.layui-body').removeClass('layui-body2')
      $('.layui-footer').removeClass('layui-footer2')
      $(this).attr('fiy-flag', 1)
    }
  })

})