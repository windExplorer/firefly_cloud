layui.use(['layer', 'form', 'jquery'], () => {
  const layer = layui.layer
  const form = layui.form
  const $ = layui.jquery
  layer.config({
    //skin: 'layer-login'
  })
  $('input[name=username]').focus()
  form.on('submit(login-submit)', (data) => {
    let ele = $(data.elem)
    if(data.field.username.length == 0 || data.field.password.length == 0)
      return
    $('input[name=password]').val('')
    ele.addClass(ele.attr('data-anim'))
    let load
    $.ajax({
      url: ele.attr('fiy-link'),
      data: data.field,
      type: 'post',
      beforeSend: function() {
        load = layer.msg('正在登录...', {
          time: 0
        })
      },
      success: (res) => {
        layer.close(load)
        if(res && res.data){
          layer.msg('登录成功，正在跳转...')
          setTimeout(function() {
            location.href = ele.attr('fiy-to')
          }, 1000)
        } else {
          layer.msg('登录失败，请检查用户名密码')
        }
      },
      complete: () => {
        $(ele).removeClass(ele.attr('data-anim'))
      }
    })
  })

})