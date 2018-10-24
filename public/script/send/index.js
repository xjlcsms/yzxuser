var unum = 0; // 有效号码数
var inputLock = false;
var tempId = 0;
var pageNoww = 1;
var pageTotall = 1;
(function(){
  // 中文输入
  document.querySelector('#sign').addEventListener('compositionstart', function(){
    inputLock = true;
  });
  document.querySelector('#sign').addEventListener('compositionend', function(){
    var content = $('#content').val();
    var end = 0;
    var title = '';
    if (this.value.length > 8) {
      this.value = this.value.substring(0, 8)
      $('#sign').val(this.value);
    }

    end = content.indexOf('】');
    if (end > -1) {
      content = content.substring(end + 1);
    }
    title = '【' + this.value + '】';
    content = title + '' + content;
    showLen(content);
    inputLock = false;
  });
  $('input[name="sign"]').bind('input propertychange', function() { 
    if (!inputLock) {
      var val = $(this).val();
      var content = $('#content').val();
      var end = 0;
      var title = '';

      if (val.length > 8) {
        val = val.substring(0, 8)
        $(this).val(val);
      }

      end = content.indexOf('】');
      if (end > -1) {
        content = content.substring(end + 1);
      }
      title = '【' + val + '】';
      content = title + '' + content;
      showLen(content);
    }
  });

  $('textarea[name="content"]').bind('input propertychange', function() { 
    var content = $(this).val()
    showLen(content);
  });

  // 短信发送方式
  $('input[name="type"]').click(function(){
    if ($(this).val() == 1) {
      $('#autoBox').removeClass('none');
      $('#xinBox').addClass('none');
    } else {
      $('#autoBox').addClass('none');
      $('#xinBox').removeClass('none');
    }
  });

  // 手机号导入方式
  $('input[name="leadtype"]').click(function(){
    if ($(this).val() == 1) {
      $('#auto').removeClass('none');
      $('#manual').addClass('none');
    } else {
      $('#auto').addClass('none');
      $('#manual').removeClass('none');
      $('#smsFile').val('');
      $('#result').addClass('none');
    }
  });

  // 文件上传
  $('#smsFile').change(function() {
    var formdata = new FormData();
    var smsfile = $("#smsFile")[0].files[0];

    formdata.append("smsfile",smsfile);
    $.ajax({
      url: '/index/send/smsfile?taskid=2',
      type: "post",
      data: formdata,
      async: true,
      cache: false,
      contentType: false,
      processData: false,
      success: function(res){
        if (res.status === true) {
          $('#fileName').text(res.data.filename);
          $('#totalNum').text(res.data.total);
          $('#useNum').text(res.data.true);
          showLen($('#content').val());
          $('#errNum').text(res.data.repeat);
          $('#reNum').text(res.data.repeat);
          $('#auto').addClass('none');
          $('#result').removeClass('none');
        } else {
          alert(res.msg)
        }
      },
      error: function(err){
        console.log(err);
      }
    });
  });

  // 删除文件
  $('#delSmsFile').click(function() {
    var params = {
      fileName: $('#fileName').text()
    }
    $.post('/index/send/delsmsf', params, function(res) {
      if (res.status === true) {
        $('#smsFile').val('');
        $('#result').addClass('none');
        $('#auto').removeClass('none');
        $('#useNum').text('');
        showLen($('#content').val());
      } else {
        alert(res.msg);
      }
    })
  });
  // 发送事件
  $('#sendBtn').click(function() {
    if ($('input[name="type"]:checked').val() == 1) {
        // 自行发送
      var params = {
        smstype: $('input[name="leadtype"]:checked').val(),
        tempId: tempId,
        type: $('select[name="smstype"]').val(),
        content: $('#content').val(),
        sign: $('#sign').val(),
        smsfile: $('#fileName').text(),
        mobiles: $('#phoneText').val()
      }
      $.post('/index/send/sms', params, function(res) {
        alert(res.msg);
        if (res.status === true) {
          window.location.href="/index/send/sendtask";
        }
      })
    } else {
      // 信嘉联创代发
      var params = {
        tempId: tempId,
        type: $('select[name="smstype"]').val(),
        area: $('input[name="area"]').val(),
        amount: $('input[name="quantity"]').val(),
        content: $('#content').val(),
        sign: $('#sign').val(),
      }
      $.post('/index/send/syssms', params, function(res) {
        alert(res.msg);
        if (res.status == true) {
          window.location.href="/index/send/sendtask";
        }
      })
    }
  });
  // 显示模板
  $('#showTemplate').click(function() {
    showTemp(1)
  });
  // 添加跳转事件
  $('#pagetion').on('click', '.jumpTo', function() {
    showTemp($(this).html())
  });
  $('#pagetion').on('click', '#pagePrev', function() {
    showTemp(pageNoww - 1)
  });
  $('#pagetion').on('click', '#pageNext', function() {
    showTemp(pageNoww + 1)
  });
  $('#pagetion').on('click', '#btnJump', function() {
    if ($('#jumpPage').val() !== '' && parseInt($('#jumpPage').val()) <= pageTotall) {
      showTemp($('#jumpPage').val())
    }
  });
  // 添加导入事件
  $('#templateTable').on('click', '.importTemplate', function() {
    var tempTitle = ''
    tempTitle = '【' + $(this).parent().prev().html() + '】';
    content = tempTitle + '' + $(this).parent().prev().prev().html();
    $('#sign').val($(this).parent().prev().html());
    $('#content').val(content);
    tempId = $(this).attr('data-id');
    $('#templateTable').empty();
    $('#tempModal').modal('hide');
    showLen(content);
  });
  // 监听发送方式
  $('input[name=type]').change(function() {
   showLen($('#content').val())
  });
  // 监听号码池
  $('input[name=leadtype]').change(function() {
    showLen($('#content').val())
  })
  // 监听手机数量输入
  $('#quantity').bind('input propertychange', function() { 
    showLen($('#content').val());
  });
  // 监听手机输入
  $('textarea[name="phoneText"]').bind('input propertychange', function() { 
    showLen($('#content').val());
  });
})()

function showLen(content) {
  var len = 0;
  var branch = 0;
  var mobileArr = [];
  len = content.length;
  if (len >= 498 ) {
    content = content.substring(0,500);
  }
  len = content.length;
  if (len <= 70 && len != 0) {
    branch = 1;
  } else {
    branch = 1;
    if ((len - 70) % 68 == 0) {
      branch += (len - 70)/68;
    } else {
      branch += (len - 70)/68 + 1
    }
  }
  $('#content').val(content);
  $('#num').text(len);
  $('#branch').text(parseInt(branch));

  if ($('input[name="type"]:checked').val() == 1 && $('input[name="leadtype"]:checked').val() == 1) {
    unum = $('#useNum').text() != '' ? parseInt($('#useNum').text()) : 0;
  }
  if ($('input[name="type"]:checked').val() == 1 && $('input[name="leadtype"]:checked').val() == 2) {
    unum = 0;
    if ($('#phoneText').val() != '') {
      if ($('#phoneText').val().indexOf('，') > -1) {
        mobileArr = $('#phoneText').val().split('，');
      } else {
        mobileArr = $('#phoneText').val().split(',');
      }
      for (var i in mobileArr) {
        if (mobileArr[i].length == 11) {
          unum++
        }
      }
    }
  }
  if ($('input[name="type"]:checked').val() == 2) {
    unum = 0;
    if ($('#quantity').val() != '') {
      unum = $('#quantity').val();
    }
  }
  $('#use').text(parseInt(branch) * unum);
}

function showTemp (page) {
  let params = {
    page: page,
    pagelimit: 5
  }
  $.post('/template/mytemp', params, function(res) {
    if (res.status === true) {
      var list = res.data.list
      if (list.length !== 0) {
        var str = '';
        for (let i in list) {
          str += '<tr>' + 
                '<td>' + list[i].template_id + '</td>' +
                '<td>' + list[i].classify + '</td>' +
                '<td>' + list[i].type + '</td>' +
                '<td>' + list[i].content + '</td>' +
                '<td>' + list[i].sign + '</td>' +
                '<td><a  class="importTemplate btn btn-primary btn-sm" data-id="' + list[i].template_id + '">导入</a></td>' +
                '</tr>'
        }
        $('#templateTable').empty();
        $('#templateTable').append(str);
        $('#dataTotal').text(res.data.total);
        // 生成分页栏
        var pageNow = res.data.page; //当前页
        pageNoww = parseInt(pageNow);
        var pageTotal = res.data.pageCount; // 总页数
        pageTotall = parseInt(pageTotal);
        var dataTotal = res.data.total; // 总条数
        var s = ''
        if (pageNow > 1) {
          s = '<li class="fa-hover none" id="pagePrev">' +
                '<a aria-label="Previous">' +
                  '<i class="fa fa-chevron-left"></i>' +
                '</a>' +
              '</li>';
        }
        
        if (pageTotal < 3) {
          if (pageTotal === 2) {
            var s1 = '<li><a class="jumpTo">1</a></li>' + '<li class="active"><a>2</a></li>';
            var s2 = '<li class="active"><a>1</a></li>' + '<li><a class="jumpTo">2</a></li>';
            s += (pageNow === 1) ? s2 : s1;
          } else {
            s += '<li class="active"><a>1</a></li>';
          }
        } else if (pageNow > 1 && pageNow < pageTotal) {
          s += '<li><a class="jumpTo">' + (parseInt(pageNow) - 1) + '</a></li>' + 
              '<li class="active"><a>' + pageNow  + '</a></li>' + 
              '<li><a class="jumpTo">' + (parseInt(pageNow) + 1)  + '</a></li>';
        } else if (pageNow === 1) {
          s += '<li class="active"><a>1</a></li>' + 
              '<li><a class="jumpTo">2</a></li>' + 
              '<li><a class="jumpTo">3</a></li>';
        } else if (pageNow === pageTotal) {
          s += '<li><a class="jumpTo">' + (parseInt(pageNow) - 2) + '</a></li>' + 
              '<li><a class="jumpTo">' + (parseInt(pageNow) - 1) + '</a></li>' + 
              '<li class="active"><a>' + pageNow + '</a></li>';
        }

        if (pageNow < pageTotal) {
          s += '<li class="fa-hover none" id="pageNext">' +
                '<a  aria-label="Next" class="i-height">' +
                  '<i class="fa fa-chevron-right"></i>' +
                '</a>'
              '</li>'
        }
        s += '<li class="pagination-li">' +
                '<sapn class="pagination-span-front">转到第</sapn><input class="pagination-input" id="jumpPage"><sapn class="pagination-span-back">页</sapn>' + 
                  '<a class="btn btn-default pageButton" id="btnJump">确定</a>' +
              '</li>';
        $('#pagetion').empty();
        $('#pagetion').append(s);
      }
    }
  });
  $('#tempModal').modal('show');
}