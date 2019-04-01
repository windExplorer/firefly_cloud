layui.use(['layer', 'element', 'jquery'], ()=>{
  const layer = layui.layer
  const element = layui.element
  const $ = layui.jquery

    /**
   * 方法
   */
  const changeProgressStatus = (n) => {
    let ele = $('[fiy-id="pc-' + n + '"]')
    let par = ele.parent()
    let ele_show = $('[fiy-id="pc-' + n + '-2"]')
    ele.removeClass('layui-icon-loading')
    ele_show.addClass('layui-anim layui-anim-upbit')
    ele_show.removeClass('layui-hide')
    par.removeClass('layui-bg-blue')
    if(par.attr('fir-status') == 1)
      par.addClass('layui-bg-green')
    else
      par.addClass('layui-bg-red')
  }

  const Request = (url, data = '', type = 'get') => {
    $.ajax({
      url: url,
      type: type,
      data: data,
      dataType: 'json',
      async: true,
      success: (res) => {
        if(1 == res.code){
          layer.open({
            content: res.msg,
            yes: (index, layero) => {
              layer.close(index)
              location.reload()
            }
          })
        } else {
          layer.open(res.msg)
        }
      },
    })
  }

  const progress_check = (n = 0) => {
    element.progress('progress-check', '0%')
    let progress = setInterval(() => {
      if(n == 100){
        changeProgressStatus(3)
        clearInterval(progress)
        var timeout = setTimeout(function() {
          let tip = $('.tip')
          tip.html(tip.attr('fiy-msg'))
          clearTimeout(timeout)
        },500)
        return
      }
      else if(n == 70)
        changeProgressStatus(2)
      else if(n == 30)
        changeProgressStatus(1)
      n += 5
      element.progress('progress-check',  n + '%')
    }, 100)
  }

  /**
   * dom操作
   * 
   */
  $(document).on('click', '[fiy-class=repair]', function() {
    Request($(this).attr('fiy-link'))
  })
  $(document).on('click', '[fiy-class=goto]', function() {
    location.href = $(this).attr('fiy-link')
  })
  /**
   * 运行
   */
  progress_check()



  






  
})