layui.use(['layer', 'jquery', 'element'], ()=>{
  const layer = layui.layer
  const $ = layui.jquery
  const element = layui.element

  let changeProgressStatus = (n) => {
    let ele = $('[fiy-id="pc-' + n + '"]')
    let par = ele.parent()
    let ele_show = $('[fiy-id="pc-' + n + '-2"]')
    ele.removeClass('layui-icon-loading')
    ele_show.addClass('layui-anim layui-anim-up')
    ele_show.removeClass('layui-hide')
    par.removeClass('layui-bg-blue')
    if(par.attr('fir-status') == 1)
      par.addClass('layui-bg-green')
    else
      par.addClass('layui-bg-red')
  }

  let n = 0
  let progress_check = setInterval(() => {
    console.log(n)
    if(n == 100){
      changeProgressStatus(3)
      clearInterval(progress_check)
      let timeout = setTimeout(() => {
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

  
})