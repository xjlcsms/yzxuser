// 添加模板
(function() {
  $('#addTemplate').click(function() {
    $('#openModal').modal('show')
  });
  $('#addBtn').click(function() {
    var params = $('#templateForm').serializeArray()
    $.post('/template/add', params, function(res) {
      if (res.status === true) {
        location.reload()
      } else {
        $('#errMsg').text(res.msg)
        $('#errMsg').show()
        setTimeout(function(){
          $('#errMsg').hide()
          $('#errMsg').text('')
        }, 2000)
      }
    })
  })
})()