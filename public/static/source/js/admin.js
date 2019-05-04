/* LAYUI-DEFINE LAYUI-CONFIG */
layui.config({
  dir: '/static/extend/layui/',
  version: false,
  debug: true,
  base: '/static/extend/plugins/'
})



/* LAYUI-USE *START* */
layui.use(['element', 'layer', 'form', 'table', 'upload', 'laydate', 'carousel'], function() {
  const layer = layui.layer
  const form = layui.form
  const element = layui.element
  const table = layui.table
  const upload = layui.upload
  const laydate = layui.laydate
  const carousel = layui.carousel
  const E = window.wangEditor
  let editor
  Render_Table()
  Render_Time()
  Render_Upload()
  Render_Viewer()
  Render_Carousel()
  
  //标记事件
  if(FLAG.search_box){
    $('[fiy-id=search-box]').slideToggle()
  }

  /* window事件 ***************************************************************************************************************** */
  //监听窗口size改变
  $(window).resize(function () {
    //table.resize(DTB_ID)
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
    Render_Carousel()
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
            case 5: //添加数据-修改数据
              //console.log(ele)
              layer.closeAll('page')
              //layer.close(layer.index)
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
  const modal = (url, title, content, area, data = '', type = 'get', is_upload = 0) => {
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
          /* form.render()
          Render_Time() */
          layer.close(load)
        },
        success: function(html){
          layer.open({
            type: 1,
            title: title,
            skin: 'fiy-layer',
            area: area,
            maxHeight: '90vh',
            content: html,
            maxmin: true,
            shade: 0,
            success: function(layero, index){
              form.render()
              Render_Time()
              element.render()
              Render_Viewer()
              if(is_upload != 0){
                Render_Upload()
                Render_wangEditor()
              }
                
              // 监听表单提交 - 此监听将获取数据库表与数据，并且会关闭弹窗
              form.on('submit(event_form)', function(data){
                console.log(data)
                let url = $(data.form).attr('fiy-url')
                let type = $(data.form).attr('method')
                console.log(data.field)
                console.log($(data.form).find('.editor').length)
                if($(data.form).find('.editor').length > 0){
                  let ele = $(data.form).find('.editor').eq(0)
                  data.field[ele.attr('name')] = editor.txt.html()
                }
                let formData = {
                    table: $(data.form).attr('fiy-table'),
                    data: data.field
                }
                console.log(formData)
                //return
                Request(url, formData, type, 5, index)
                return false
              })
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
  // 监听表单提交-此方法不主动获取数据库表，仅仅将表单数据提交到url进行处理
  form.on('submit(*)', function(data){
    let url = $(data.form).attr('fiy-url')
    let type = $(data.form).attr('method')
    console.log(data.field)
    Request(url, data.field, type)
    return false
  })
  //监听页面层
  form.on('submit(event_page_form)', function(data){
    let url = $(data.form).attr('fiy-url')
    let type = $(data.form).attr('method')
    console.log(data.field)
    let formData = {
        table: $(data.form).attr('fiy-table'),
        data: data.field
    }
    Request(url, formData, type)
    return false
  })
  // 监听搜索
  form.on('submit(form_search)', function(data){
    //let url = $(data.form).attr('fiy-url')
    //let type = $(data.form).attr('method')
    let arr = {}
    let blur= 0
    for(let item in data.field){
      if(item != 'blur'){
        if(data.field[item].length > 0)
          arr[item] = data['field'][item]
      }else{
        blur = data['field'][item]
      }
    }
    if(Object.keys(arr).length == 0){
      layer.msg('请输入条件再搜索')
      return
    }
    let where = {
      table: Get_Dtb_Table(),
      data: arr,
      blur: blur
    }
    Render_Table(where)
    return false
  })
  // 监听数据表格switch
  form.on('switch(tpl_status_switch)', function(data){
    let url = DTB_STATUS
    let ele = $(data.elem)
    let status = data.value == 0 ? 1 : 0
    Request(url, {table: Get_Dtb_Table(), id: Get_Dtb_Item_Id(ele), status: status}, 'POST', 2, ele)
  })  
  //监听user_id下拉框
  form.on('select(user_id)', function(data){
    let ele = $(data.elem)
    $(document).find('input[name=to]').val(ele.find('option:selected').attr('fiy-email'))
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
        modal(th.attr('fiy-event-url'), `添加`, ``, `800px,auto`, data, 'post', 1)
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
        modal(th.attr('fiy-event-url'), `编辑`, ``, `800px,auto`, data, 'post', 1)
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
      modal(th.parent().attr('fiy-url'), th.attr('fiy-title') || `信息`, th.attr('fiy-content') || ``, th.attr('fiy-area') || `800px,auto` , data, 'post')
    } else if(layEvent === 'del'){ //删除
      layer.confirm('真的删除此项么', {icon: 3, title:'删除提示'}, function(index){
        layer.close(index)
        Request(th.parent().attr('fiy-url'), data, 'post', 3, obj)
      })
    } else if(layEvent === 'edit'){ //编辑
      modal(th.parent().attr('fiy-url'), th.attr('fiy-title') || `信息`, th.attr('fiy-content') || ``, th.attr('fiy-area') || `800px,auto` , data, 'post', 1)
      //同步更新缓存对应的值
      /* obj.update({
        username: '123'
        ,title: 'xxx'
      }) */
    }
  })
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
    laydate.render({
      elem: '#search_born',
      type: 'date',
      range: '~'
    })

    laydate.render({
      elem: '#add_regtime',
      type: 'datetime',
      range: false,
      value: new Date()
    })
    laydate.render({
      elem: '#add_uptime',
      type: 'datetime',
      range: false,
      value: new Date()
    })
    laydate.render({
      elem: '#edit_regtime',
      type: 'datetime',
      range: false,
    })
    laydate.render({
      elem: '#edit_uptime',
      type: 'datetime',
      range: false,
    })

    laydate.render({
      elem: '#add_born',
      type: 'date',
      range: false,
    })
    laydate.render({
      elem: '#edit_born',
      type: 'date',
      range: false,
    })
    laydate.render({
      elem: '#add_time',
      type: 'datetime',
      range: false,
    })
    laydate.render({
      elem: '#edit_time',
      type: 'datetime',
      range: false,
    })
  }
  // 重置数据表格
  function Render_Table(where = '', id = '#'+DTB_ID){
    if(where.length == 0)
      where = {
        table: Get_Dtb_Table()
      }
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
      where: where,
      cols: [cols],
      even: false,
      toolbar: 'default',
      defaultToolbar: ['filter', 'print', 'exports'],
      done: function() {
        close_loop_anim()
        Render_Viewer()
      }
      
    })
  }

  /* 图片查看器初始化 */
  function Render_Viewer(){
    /* if($('[fiy-photo-list]').length > 0) */
      /* viewer = new Viewer($('[fiy-photo-list]')[0], {
        zIndex: 19951020
      }) */
      $('[fiy-photo]').viewer({
        zIndex : '19951020',
        url:    'src'
      })
  }

  

  /* 上传图片 */
  function Render_Upload(){
    upload.render({
      elem: '[fiy-upload]',
      url: UPLOAD_URL,
      data: {
        table: Get_Dtb_Table()
      },
      field: 'file',
      size: UPLOAD_MAXSIZE,
      accept: 'file',
      //acceptMime: 'image/*',
      exts: UPLOAD_EXT,
      multiple: true,
      auto: false,
      choose: function(obj){
        let th = this.item
        let type = parseInt(th.attr('fiy-upload-type'))
        //预读本地文件，如果是多文件，则会遍历。(不支持ie8/9)
        //console.log(obj.resetFile.length)
        obj.preview(function(index, file, result){
          let mime = file.type
          console.log(mime)
          let img
          if(mime.indexOf('image') > -1){
            img = `<div class="img-box"><img src='${result}' fiy-photo /><span class='img-title' fiy-photo-id='${index}'></span></div>`
          }else{
            img = `<div class="img-box"><img src='/static/source/img/file.png' fiy-photo /><span class='img-title' fiy-photo-id='${index}'></span></div>`
          }
          
          if(type == 1){
            th.nextAll('[fiy-photo-list]').html(img)
          }else if(type == 2){
            th.nextAll('[fiy-photo-list]').append(img)
          }else if(type == 3){
            th.nextAll('[fiy-photo-list]').html(img)
          }else if(type == 4){
            th.nextAll('[fiy-photo-list]').append(img)
          }
          
          getFileMd5($(`[fiy-photo-id='${index}']`), file, res => {
            console.log('md5:' + res)
            $.get(Check_File_Exist, {md5: res}, function(res){
              console.log(res)
              if(res.code == 0){
                obj.upload(index, file)
              }else{
                layer.msg('上传成功')
                let input = th.prev().find('input')
                if(type == 1){
                  input.val(res.data.net_path)
                }else if(type == 2){
                  if(input.val().length == 0)
                    input.val(res.data.net_path)
                  else
                    input.val(input.val()+';'+res.data.net_path)
                }else if(type == 3){
                  input.val(res.data.path)
                }else if(type == 4){
                  if(input.val().length == 0)
                    input.val(res.data.path)
                  else
                    input.val(input.val()+';'+res.data.path)
                }
                Render_Viewer()
              }
            })
            
            //obj.upload(index, file); //对上传失败的单个文件重新上传，一般在某个事件中使用
          })
          /* getFileMd5_2(file, res => {
            console.log(res)
          })
          getFileSha1(file, res => {
            console.log(res)
          }) */
          
          //obj.resetFile(index, file, '123.jpg'); //重命名文件名，layui 2.3.0 开始新增
          
          //这里还可以做一些 append 文件列表 DOM 的操作
          
          //delete files[index]; //删除列表中对应的文件，一般在某个事件中使用
        })
      },
      before: function(obj){ //obj参数包含的信息，跟 choose回调完全一致，可参见上文。
        layer.load() //上传loading
      },
      allDone: function(obj){
        /* console.log(obj)
        console.log(obj.total)
        console.log(obj.successful)
        console.log(obj.aborted) */
        Render_Viewer()
      },
      done: function(res, index, upload){
        layer.closeAll('loading')
        let th = this.item
        let type = parseInt(th.attr('fiy-upload-type'))
        let input = th.prev().find('input')
        layer.msg(res.msg)
        console.log(res)
        if(res.code == 1){
          if(type == 1){
            input.val(res.data.net_path)
          }else if(type == 2){
            if(input.val().length == 0)
              input.val(res.url)
            else
              input.val(input.val()+';'+res.url)
          }else if(type == 3){
            input.val(res.data.path)
          }else if(type == 4){
            if(input.val().length == 0)
              input.val(res.phy_url)
            else
              input.val(input.val()+';'+res.phy_url)
          }
          Render_Viewer()
        }
      },
      error: function(index, upload) {
        layer.closeAll('loading')
      }
    })
  }
  /* 文件md5操作 */
  function getFileMd5(ele , file, callback){

    var blobSlice = File.prototype.slice || File.prototype.mozSlice || File.prototype.webkitSlice,
    //file = this.files[0],
    chunkSize = 2 * 1024 * 1024,                             // Read in chunks of 2MB
   // chunks = Math.ceil(file.size / chunkSize),
    chunks = 100,
    currentChunk = 0,
    spark = new SparkMD5.ArrayBuffer(),
    fileReader = new FileReader();

    fileReader.onload =  (e) => {
        //console.log('read chunk nr', currentChunk + 1, 'of', chunks);
        spark.append(e.target.result);                   // Append array buffer
        currentChunk++;
        let percent = ((currentChunk/chunks)*100).toFixed(0) + '%'
        //element.progress('event-file-progress', percent);
        ele.text(percent)
        if (currentChunk < chunks) {
            loadNext();
        } else {
          //console.log('finished loading');
          ele.fadeOut(500)
          callback(spark.end());  // Compute hash
          //return spark.end()
        }
    }

    fileReader.onerror = function () {
        console.warn('oops, something went wrong.');
    }

    function loadNext() {
        var start = currentChunk * chunkSize,
            end = ((start + chunkSize) >= file.size) ? file.size : start + chunkSize;

        fileReader.readAsArrayBuffer(blobSlice.call(file, start, end));
    }

    loadNext();

  }

  /* 获取文件sha1  */
  function getFileSha1(file, callback){
    var reader = new FileReader();
    reader.onload = (event) => {
      var res = event.target.result;
      res = CryptoJS.lib.WordArray.create(res);
      var sha1 = CryptoJS.SHA1(res).toString();
      callback('3:' + sha1);
    };
    reader.readAsArrayBuffer(file);
  }
  
  /* 获取文件md5_2 */
  function getFileMd5_2(file, callback){
    var reader = new FileReader();
    reader.onload = (event) => {
      var res = event.target.result;
      res = CryptoJS.lib.WordArray.create(res);
      var sha1 = CryptoJS.MD5(res).toString();
      callback('2:' + sha1);
    };
    reader.readAsArrayBuffer(file);
  }
  
  /* 富文本 */
  function Render_wangEditor(){
    if($('body').find('.editor').length > 0){
      let html = ``
      if($('.editor').attr('fiy-event') == 'edit'){
        html = $('.editor').find('.content').html()
      }
      editor = new E('.editor')
      editor.customConfig.debug = true
      // 或者 var editor = new E( document.getElementById('editor') )
      //editor.customConfig.zIndex = 1
      editor.customConfig.uploadImgServer = UPLOAD_IMG_WANGEDITOR
      editor.customConfig.uploadImgMaxSize = UPLOAD_MAXSIZE
      editor.customConfig.uploadFileName = 'file[]'
      editor.customConfig.uploadImgParams = {
        table: Get_Dtb_Table()
      }
      /* editor.customConfig.uploadImgHooks = {
        before: function (xhr, editor, files) {
            // 图片上传之前触发
            // xhr 是 XMLHttpRequst 对象，editor 是编辑器对象，files 是选择的图片文件
            
            // 如果返回的结果是 {prevent: true, msg: 'xxxx'} 则表示用户放弃上传
            // return {
            //     prevent: true,
            //     msg: '放弃上传'
            // }
        },
        success: function (xhr, editor, result) {
            // 图片上传并返回结果，图片插入成功之后触发
            // xhr 是 XMLHttpRequst 对象，editor 是编辑器对象，result 是服务器端返回的结果
        },
        fail: function (xhr, editor, result) {
            // 图片上传并返回结果，但图片插入错误时触发
            // xhr 是 XMLHttpRequst 对象，editor 是编辑器对象，result 是服务器端返回的结果
        },
        error: function (xhr, editor) {
            // 图片上传出错时触发
            // xhr 是 XMLHttpRequst 对象，editor 是编辑器对象
        },
        timeout: function (xhr, editor) {
            // 图片上传超时时触发
            // xhr 是 XMLHttpRequst 对象，editor 是编辑器对象
        },
    
        // 如果服务器端返回的不是 {errno:0, data: [...]} 这种格式，可使用该配置
        // （但是，服务器端返回的必须是一个 JSON 格式字符串！！！否则会报错）
        customInsert: function (insertImg, result, editor) {
            // 图片上传并返回结果，自定义插入图片的事件（而不是编辑器自动插入图片！！！）
            // insertImg 是插入图片的函数，editor 是编辑器对象，result 是服务器端返回的结果
    
            // 举例：假如上传图片成功后，服务器端返回的是 {url:'....'} 这种格式，即可这样插入图片：
            var url = result.url
            insertImg(url)
    
            // result 必须是一个 JSON 格式字符串！！！否则报错
        }
      } */
    
      

      editor.create()
      editor.txt.html(html)
    }

  }

  /* 轮播图 */
  function Render_Carousel(ele = $('[fiy-id=carousel]')){
    layui.each(ele, function(index, elem){
      let th = $(elem)
      let width = th.attr('fiy-width') || '100%'
      let height = th.attr('fiy-height') || '140px'
      let trigger = th.attr('fiy-tigger') || 'hover'
      let autoplay = th.attr('fiy-autoplay') || false
      let arrow = th.attr('fiy-arrow') || 'none'
  
      carousel.render({
        elem: th,
        width: width,
        arrow: arrow,
        autoplay: autoplay,
        trigger: trigger,
        height: height,
        //anim: anim
      })

    })
    
  }  

function Render_Echarts() {
    echarts_file()
    echarts_user_location()
    echarts_test1()
    echarts_test2()
    echarts_test3()
    echarts_test4()
}

/* echarts */
echarts_file()
echarts_user_location()
echarts_test1()
echarts_test2()
echarts_test3()
echarts_test4()
// 文件统计
function echarts_file() {
    var dom = document.getElementById('file_total')
    if(!dom){
        return 
    }
    var myChart = echarts.init(dom)

    /* $.ajax({
        type: 'get',

    }) */

    // 指定图表的配置项和数据
    var option = {
        title: {
            text: '文件统计'
        },
        tooltip: {},
        legend: {
            data:['数量']
        },
        xAxis: {
            data: ["软件","压缩包","图片","视频","文档","其他"]
        },
        yAxis: {},
        series: [{
            name: '数量',
            type: 'bar',
            data: [5, 20, 36, 10, 10, 20]
        }]
    };

    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);
}
// 用户来源
function echarts_user_location() {
    var dom = document.getElementById('user_location')
    if(!dom){
        return 
    }
    var myChart = echarts.init(dom)

    // 指定图表的配置项和数据
    var option = {
        title : {
            text: '南丁格尔玫瑰图',
            subtext: '纯属虚构',
            x:'center'
        },
        tooltip : {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        legend: {
            x : 'center',
            y : 'bottom',
            data:['rose1','rose2','rose3','rose4','rose5','rose6','rose7','rose8']
        },
        toolbox: {
            show : true,
            feature : {
                mark : {show: true},
                dataView : {show: true, readOnly: false},
                magicType : {
                    show: true,
                    type: ['pie', 'funnel']
                },
                restore : {show: true},
                saveAsImage : {show: true}
            }
        },
        calculable : true,
        series : [
            {
                name:'面积模式',
                type:'pie',
                radius : [30, 110],
                center : ['50%', '50%'],
                roseType : 'area',
                data:[
                    {value:10, name:'rose1'},
                    {value:5, name:'rose2'},
                    {value:15, name:'rose3'},
                    {value:25, name:'rose4'},
                    {value:20, name:'rose5'},
                    {value:35, name:'rose6'},
                    {value:30, name:'rose7'},
                    {value:40, name:'rose8'}
                ]
            }
        ]
    };

    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);
}

// 
function echarts_test1() {
    var dom = document.getElementById('echarts_test1')
    if(!dom){
        return 
    }
    var myChart = echarts.init(dom)

    // 指定图表的配置项和数据
    var option = {
        angleAxis: {
        },
        radiusAxis: {
            type: 'category',
            data: ['周一', '周二', '周三', '周四'],
            z: 10
        },
        polar: {
        },
        series: [{
            type: 'bar',
            data: [1, 2, 3, 4],
            coordinateSystem: 'polar',
            name: 'A',
            stack: 'a'
        }, {
            type: 'bar',
            data: [2, 4, 6, 8],
            coordinateSystem: 'polar',
            name: 'B',
            stack: 'a'
        }, {
            type: 'bar',
            data: [1, 2, 3, 4],
            coordinateSystem: 'polar',
            name: 'C',
            stack: 'a'
        }],
        legend: {
            show: true,
            data: ['A', 'B', 'C']
        }
    };

    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);
}

function echarts_test2() {
    var dom = document.getElementById('echarts_test2')
    if(!dom){
        return 
    }
    var myChart = echarts.init(dom)

    // 指定图表的配置项和数据
    var option = {
        title: {
            text: '基础雷达图'
        },
        tooltip: {},
        legend: {
            data: ['预算分配（Allocated Budget）', '实际开销（Actual Spending）']
        },
        radar: {
            // shape: 'circle',
            name: {
                textStyle: {
                    color: '#fff',
                    backgroundColor: '#999',
                    borderRadius: 3,
                    padding: [3, 5]
               }
            },
            indicator: [
               { name: '销售（sales）', max: 6500},
               { name: '管理（Administration）', max: 16000},
               { name: '信息技术（Information Techology）', max: 30000},
               { name: '客服（Customer Support）', max: 38000},
               { name: '研发（Development）', max: 52000},
               { name: '市场（Marketing）', max: 25000}
            ]
        },
        series: [{
            name: '预算 vs 开销（Budget vs spending）',
            type: 'radar',
            // areaStyle: {normal: {}},
            data : [
                {
                    value : [4300, 10000, 28000, 35000, 50000, 19000],
                    name : '预算分配（Allocated Budget）'
                },
                 {
                    value : [5000, 14000, 28000, 31000, 42000, 21000],
                    name : '实际开销（Actual Spending）'
                }
            ]
        }]
    };

    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);
}

function echarts_test3() {
    var dom = document.getElementById('echarts_test3')
    if(!dom){
        return 
    }
    var myChart = echarts.init(dom)

    // 指定图表的配置项和数据
    var axisData = ['周一','周二','周三','很长很长的周四','周五','周六','周日'];
    var data = axisData.map(function (item, i) {
        return Math.round(Math.random() * 1000 * (i + 1));
    });
    var links = data.map(function (item, i) {
        return {
            source: i,
            target: i + 1
        };
    });
    links.pop();
    var option = {
        title: {
            text: '笛卡尔坐标系上的 Graph'
        },
        tooltip: {},
        xAxis: {
            type : 'category',
            boundaryGap : false,
            data : axisData
        },
        yAxis: {
            type : 'value'
        },
        series: [
            {
                type: 'graph',
                layout: 'none',
                coordinateSystem: 'cartesian2d',
                symbolSize: 40,
                label: {
                    normal: {
                        show: true
                    }
                },
                edgeSymbol: ['circle', 'arrow'],
                edgeSymbolSize: [4, 10],
                data: data,
                links: links,
                lineStyle: {
                    normal: {
                        color: '#2f4554'
                    }
                }
            }
        ]
    };

    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);
}

function echarts_test4() {
    var dom = document.getElementById('echarts_test4')
    if(!dom){
        return 
    }
    var myChart = echarts.init(dom)

    // 指定图表的配置项和数据
    var option = {
        title : {
            text: '某站点用户访问来源',
            subtext: '纯属虚构',
            x:'center'
        },
        tooltip : {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        legend: {
            orient: 'vertical',
            left: 'left',
            data: ['直接访问','邮件营销','联盟广告','视频广告','搜索引擎']
        },
        series : [
            {
                name: '访问来源',
                type: 'pie',
                radius : '55%',
                center: ['50%', '60%'],
                data:[
                    {value:335, name:'直接访问'},
                    {value:310, name:'邮件营销'},
                    {value:234, name:'联盟广告'},
                    {value:135, name:'视频广告'},
                    {value:1548, name:'搜索引擎'}
                ],
                itemStyle: {
                    emphasis: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            }
        ]
    };

    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);
}

 
  /* 默认操作 **************************************************************************************************************** */
  // PJAX开始
  $(document).on('pjax:start', () => {
    //pjax_load = layer.load(2)
    NProgress.start()
  })
  // PJAX结束
  $(document).on('pjax:end', () => {
    //layer.close(pjax_load)
    PJAX_END()
    Render_Echarts()
    NProgress.done()
  })
  // PJAX事件
  $(document).pjax('a[data-pjax]', '#pjax-container', {
    cache: false
  })
  // 退出
  $(document).on('click', 'a[fiy-logout]', function() {
    let th = $(this)
    layer.confirm('真的退了么?', {icon: 3, title: '退出提示'}, function(index){
      layer.close(index)
      Request(th.attr('fiy-link'))
    })
    
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
    modal(th.attr('fiy-modal') || '', th.attr('fiy-title') || `信息`, th.attr('fiy-content') || ``, th.attr('fiy-area') || `800px`, th.attr('fiy-is-upload') || 0)
  })
  // 页面重载/表格重载
  $(document).on('click', 'a[fiy-reload]', function(e) {
    open_loop_anim()
    Page_Reload()
    //Table_Reload()
  })
  //表格重载
  $(document).on('click', 'a[table_render]', function() {
    if($('#'+DTB_ID).length == 0)
      return
    open_loop_anim()
    Render_Table()
  })
  //打开图标库
  $(document).on('click', 'a[fiy-id=select-icon]', function() {
    let load
    $.ajax({
      type: 'get',
      url: ICON_URL,
      beforeSend: function(){
        load = layer.load(2)
      },
      complete: function() {
        layer.close(load)
      },
      success: function(html){
        layer.open({
          type: 1,
          title: '图标库',
          content: html,
          area: ["800px", "700px"],
          maxmin: true,
          shade: 0,
          skin: 'fiy-layer',
          success: function(layero, index){
            //点击图标库中图标获取图标
            $(document).on('click', '.icon-ku li', function(){
              $('a[fiy-id=show-icon]').find('i').attr('class', $(this).find('i').attr('class'))
              $('a[fiy-id=show-icon]').parent().prev().find('input').val($(this).find('i').attr('class'))
              layer.close(index)
            })
          }
        })
      }
    })
  })
  //收起搜索面板
  $(document).on('click', '[fiy-id=search-box-tag]', function() {
    $('[fiy-id=search-box]').slideToggle()
  })
  //提示
  $(document).on('mouseover', '[fiy-tips]', function() {
    let th = $(this)
    let index = layer.tips(th.attr('fiy-tips'), th, {
      tips: th.attr('fiy-tips-area') || 1,
      time: 0
    })
    th.on('mouseout', function(){
      layer.close(index)
    })
  })  
  //logo刷新页面
  $(document).on('click', '.layui-logo', function() {
    location.reload()
  })
  //选择图片 - 暂时只能单选，会被替换
  $(document).on('click', '[fiy-file-selected="file-net-path"]', function(){
    let th = $(this)
    $('input[file-select="file-net-path"]').val(th.attr('fiy-file-url'))
    let img = `<div class="img-box"><img src='${th.attr('fiy-file-url')}' fiy-photo />`
    $('[fiy-photo-list]').html(img)
    layer.close(layer.index)
    Render_Viewer()
  })
  //选择文件 - 暂时只能单选，不会被替换
  $(document).on('click', '[fiy-file-selected="file-path"]', function(){
    let th = $(this)
    let img
    if( $('input[file-select="file-path"]').val().length == 0)
      $('input[file-select="file-path"]').val(th.attr('fiy-file-path'))
    else
      $('input[file-select="file-path"]').val($('input[file-select="file-path"]').val() + `;` + th.attr('fiy-file-path'))
    if(th.attr('fiy-mime').indexOf('image') > -1)
      img = `<div class="img-box"><img src='${th.attr('fiy-file-url')}' fiy-photo />`
    else
      img = `<div class="img-box"><img src='/static/source/img/file.png' fiy-photo title='${th.attr('fiy-file-path')}' />`
    $('[fiy-photo-list]').append(img)
    layer.close(layer.index)
    Render_Viewer()
  })







})
/*  LAYUI-USE *END*  */