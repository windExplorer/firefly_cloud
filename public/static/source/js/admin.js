/* LAYUI-DEFINE LAYUI-CONFIG */
layui.config({
  dir: '/static/extend/layui/',
  version: false,
  debug: true,
  base: '/static/extend/plugins/'
})



/* LAYUI-USE *START* */
layui.use(['element', 'layer', 'form', 'table', 'upload', 'laydate'], function() {
  const layer = layui.layer
  const form = layui.form
  const element = layui.element
  const table = layui.table
  const upload = layui.upload
  const laydate = layui.laydate
  let pjax_load
  Render_Table()
  Render_Time()
  
  /* window事件 ***************************************************************************************************************** */
  //监听窗口size改变
  $(window).resize(function () {
    table.resize(DTB_ID)
  })


  /* 配置 */
  /* layer.config({
    skin: 'fiy-layer'
  }) */
  
  /* const 函数 ******************************************************************************************************************* */
  // fiy-loop>i开启循环旋转动画
  const open_loop_anim = () => {
    $('[fiy-loop]').find('i').addClass('layui-anim layui-anim-rotate layui-anim-loop')
  }
  // fiy-loop>i关闭循环旋转动画
  const close_loop_anim = () => {
    $('[fiy-loop]').find('i').removeClass('layui-anim layui-anim-rotate layui-anim-loop')
  }
  // 页面重载
  const Page_Reload = () => {
    let url = location.pathname
    $.pjax({url: url, container: '#pjax-container'})
  }
  // 数据表格重载
  const Table_Reload = () => {
    table.reload(DTB_ID)
    close_loop_anim()
  }
  // PJAX结束后的操作
  const PJAX_END = () => {
    Change_This()
    form.render()
    element.render()
    Render_Table()
    Render_Time()
    close_loop_anim()
  }
  // 设置数据表格状态
  const Set_Dtb_Status = (ele) => {
    if(ele.attr('value') == 1){
      ele.removeAttr('checked')
      ele.attr('value', 0)
    }
    else{
      ele.attr('checked')
      ele.attr('value', 1)
    }
  }
  // 获取数据表格条目id
  const Get_Dtb_Item_Id = (ele) => {
    return ele.attr('fiy-id')
  }
  // layui-this 导航改变后
  const Change_This = () => {
    let thUrl = location.pathname
    $('dd[fiy-side-tpl]').each((index, elem) => {
        if($(elem).children('a').attr('href') == thUrl){
            $('dd[fiy-side-tpl]').removeClass('layui-this')
            $(elem).addClass('layui-this')
            return false
        }
    })
  }
  // 普通请求
  const Request = (url, data = '', type = 'get', req_type = 1, ele = '') => {
    let load
    $.ajax({
      url: url,
      type: type,
      data: data,
      dataType: 'json',
      async: true,
      beforeSend: function() {
        load = layer.load(2)
      },
      success: (res) => {
        console.log(res)
        //data code msg
        if(1 == res.code){
          layer.msg(res.msg, {
            icon: 1,
            anim: 1
          })
          switch(req_type){
            case 1: //普通-更新页面
              Page_Reload()
              break
            case 2: //行状态更新
              Set_Dtb_Status(ele)
              break
            case 3: //行event删除
              ele.del()
              break
            case 4: //更新表格
              Table_Reload()
              break
            default:
              Page_Reload() 
              break
          }
          
        } else {
          layer.msg(res.msg, {
            icon: 2,
            anim: 6
          })
        }
      },
      error: function(e) {
        console.log(e)
        layer.msg('请求失败!', {
          icon: 2,
          anim: 6
        })
      },
      complete: function() {
        layer.close(load)
      }
    })
  }

  /* 弹出层-打开新页面 ******************************************************************************************************************* */
  /* modal open href url */
  const modal = (url, title, content, area, data = '', type = 'get') => {
    if(area.indexOf(`,`) > -1) {
      area = area.split(',')
    }
    let load
    if(content == ``)
      $.ajax({
        url: url,
        type: type,
        data: data,
        beforeSend: function(){
          load = layer.load(2)
        },
        complete: function(){
          form.render()
          layer.close(load)
        },
        success: function(html){
          layer.open({
            type: 1,
            title: title,
            skin: 'fiy-layer',
            area: area,
            content: html,
            success: function(layero, index){
              form.render()
            },
            cancel: function(index1, layero){ 
              layer.confirm('确定关闭么?', {icon: 3, title:'关闭提示'}, function(index2){
                layer.close(index1)
                layer.close(index2)
              })
              return false
            } 
          })
        }
      })
    else
      layer.open({
        title: title,
        area: area,
        content: content,
        btn: ['确认']
      })
  }



  /* layui事件监听 **************************************************************************************************************** */
  // 监听表单提交
  form.on('submit(*)', function(data){
    let url = $(data.form).attr('fiy-url')
    let type = $(data.form).attr('method')
    console.log(data.field)
    Request(url, data.field, type)
    return false
  })

  // 监听数据表格switch
  form.on('switch(tpl_status_switch)', function(data){
    let url = DTB_STATUS
    let ele = $(data.elem)
    let status = data.value == 0 ? 1 : 0
    Request(url, {table: Get_Dtb_Table(), id: Get_Dtb_Item_Id(ele), status: status}, 'POST', 2, ele)
  })  
  //监听行双击事件
  table.on('rowDouble(dtb)', function(obj){
    console.log(obj.tr) //得到当前行元素对象
    console.log(obj.data) //得到当前行数据
    //obj.del(); //删除当前行
    //obj.update(fields) //修改当前行数据
    //obj 同上
    console.log('双击')
  });
  

  // 监听数据表格头部工具
  table.on('toolbar(dtb)', function(obj){
    let checkStatus = table.checkStatus(obj.config.id) //获取选中项
    let data = {
      id: 0,
      table: Get_Dtb_Table(),
      event: obj.event
    }
    let th = $(`#${DTB_ID}`)
    switch(obj.event){
      case 'add':
        modal(th.attr('fiy-event-url'), `添加`, ``, `800px` , data, 'post')
      break
      case 'update':
        if(checkStatus.data.length > 1){
          layer.msg('请仅选择一项')
          return
        }
        if(checkStatus.data.length == 0){
          layer.msg('请选择一项进行操作')
          return
        }
        data.id = checkStatus.data[0].id
        data.event = `edit`
        modal(th.attr('fiy-event-url'), `编辑`, ``, `800px` , data, 'post')
      break
      case 'delete':
        if(checkStatus.data.length == 0){
          layer.msg('请至少选择一项进行操作')
          return
        }
        data.event = 'del'
        data.id = []
        checkStatus.data.map(function(item, index){
          data.id.push(item.id)
        })
        layer.confirm('真的删除这些项目么', {icon: 3, title:'删除提示'}, function(index){
          layer.close(index)
          Request(th.attr('fiy-event-url'), data, 'post', 4)
        })
      break
    }
  })
  //监听数据表格行工具条
  table.on('tool(dtb)', function(obj){
    let layEvent = obj.event
    let th = $(this)
    let data = {
      id: th.parent().attr('fiy-id'),
      table: Get_Dtb_Table(),
      event: layEvent
    }
    if(layEvent === 'view'){ //查看      
      modal(th.parent().attr('fiy-url'), th.attr('fiy-title') || `信息`, th.attr('fiy-content') || ``, th.attr('fiy-area') || `800px` , data, 'post')
    } else if(layEvent === 'del'){ //删除
      layer.confirm('真的删除此项么', {icon: 3, title:'删除提示'}, function(index){
        layer.close(index)
        Request(th.parent().attr('fiy-url'), data, 'post', 3, obj)
      })
    } else if(layEvent === 'edit'){ //编辑
      modal(th.parent().attr('fiy-url'), th.attr('fiy-title') || `信息`, th.attr('fiy-content') || ``, th.attr('fiy-area') || `800px` , data, 'post')
      //同步更新缓存对应的值
      obj.update({
        username: '123'
        ,title: 'xxx'
      })
    }
  })


  /* function **************************************************************************************************************** */

  // 获取数据库表
  function Get_Dtb_Table() {
    return $(`#${DTB_ID}`).attr('fiy-table')
  }
  // 重置时间
  function Render_Time(){
    laydate.render({
      elem: '#search_regtime',
      type: 'datetime',
      range: '~'
    })
    laydate.render({
      elem: '#search_uptime',
      type: 'datetime',
      range: '~'
    })
  }
  // 重置数据表格
  function Render_Table(id = '#'+DTB_ID){
    let ele = $(id)
    if(ele.length == 0)
      return
    let cols = []
    ele.find('th').each(function(index, item){
      let th_ = $(item).html()
      cols.push($.parseJSON(th_))
    })
    table.render({
      elem: id, 
      height: 'auto', 
      url: ele.attr('fiy-url'),
      page: true,
      where: {
        table: Get_Dtb_Table()
      },
      cols: [cols],
      even: false,
      toolbar: 'default',
      defaultToolbar: ['filter', 'print', 'exports'],
      
    })
  }

  /* 表单验证 */
  form.verify({
    username: function(value, item){ //value：表单的值、item：表单的DOM对象
      if(!new RegExp("^[a-zA-Z0-9_\u4e00-\u9fa5\\s·]+$").test(value)){
        return '用户名不能有特殊字符';
      }
      if(/(^\_)|(\__)|(\_+$)/.test(value)){
        return '用户名首尾不能出现下划线\'_\'';
      }
      if(/^\d+\d+\d$/.test(value)){
        return '用户名不能全为数字';
      }
    },
    
    //我们既支持上述函数式的方式，也支持下述数组的形式
    //数组的两个值分别代表：[正则匹配、匹配不符时的提示文字]
    pass: [
      /^[\S]{5,20}$/
      ,'密码必须5到20位，且不能出现空格'
    ] ,
    pass2: function(value, item){
      if($(item).parents('.layui-form-item').prev().find('input[name=new]').val() != value){
        return '两次密码不一致'
      }
    }
  })


  /* 默认操作 **************************************************************************************************************** */
  // PJAX开始
  $(document).on('pjax:start', () => {
    pjax_load = layer.load(2)
  })
  // PJAX结束
  $(document).on('pjax:end', () => {
    layer.close(pjax_load)
    PJAX_END()
  })
  // PJAX事件
  $(document).pjax('a[data-pjax]', '#pjax-container', {
    cache: false
  })
  // 退出
  $(document).on('click', 'a[fiy-logout]', function() {
    Request($(this).attr('fiy-link'))
  })
  // 侧边栏隐藏
  $(document).on('click', 'a[fiy-shrink]', function() {
    let th = $(this)
    let th_i = th.children('i')
    let flag = th.attr('fiy-flag')
    if(1 == flag){
      th_i.removeClass('layui-icon-shrink-right')
      th_i.addClass('layui-icon-spread-left')
      $('.layui-layout-admin .layui-side').addClass('layui-side2')
      $('.layui-body').addClass('layui-body2')
      $('.layui-footer').addClass('layui-footer2')
      th.attr('fiy-flag', 2)
    } else {
      th_i.removeClass('layui-icon-spread-left')
      th_i.addClass('layui-icon-shrink-right')
      $('.layui-layout-admin .layui-side').removeClass('layui-side2')
      $('.layui-body').removeClass('layui-body2')
      $('.layui-footer').removeClass('layui-footer2')
      th.attr('fiy-flag', 1)
    }
    Render_Table()
  })
  // 打开modal
  $(document).on('click', '[fiy-modal]', function() {
    let th = $(this)
    modal(th.attr('fiy-modal') || '', th.attr('fiy-title') || `信息`, th.attr('fiy-content') || ``, th.attr('fiy-area') || `800px`)
  })
  // 页面重载/表格重载
  $(document).on('click', 'a[fiy-reload]', function(e) {
    open_loop_anim()
    Page_Reload()
    //Table_Reload()
  })
  //表格重载
  $(document).on('click', 'a[table_reload]', function() {
    if($('#'+DTB_ID).length == 0)
      return
    open_loop_anim()
    Table_Reload()
  })

  










})
/*  LAYUI-USE *END*  */