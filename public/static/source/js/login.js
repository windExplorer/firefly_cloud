layui.use(['layer', 'form'], () => {
  const layer = layui.layer
  const form = layui.form
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
        //console.log(res)
        if(res || res.data ||res.data.length > 0){
          location.href = ele.attr('fiy-to')
        }
      },
      complete: () => {
        $(ele).removeClass(ele.attr('data-anim'))
      }
    })
  })

})