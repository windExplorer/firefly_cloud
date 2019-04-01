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
    $.ajax({
      url: ele.attr('fiy-link'),
      data: data.field,
      type: 'post',
      success: (res) => {
        if(res && res.data){
          location.href = ele.attr('fiy-to')
        } else {
          return
        }
      },
      complete: () => {
        $(ele).removeClass(ele.attr('data-anim'))
      }
    })
  })

})