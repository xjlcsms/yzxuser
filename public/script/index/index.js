(function() {
  var showStatus = false
  // 当天发送的短信数据
  $.get('/data/daysend', function(res) {
    $('#generalSend').text(res.data.generalSend);
    $('#marketSend').text(res.data.marketSend);
  });
  $.get('/data/dayrecharge', function(res) {
    $('#general').text(res.data.general);
    $('#market').text(res.data.market);
  });

  // 查看密码状态
  $('#changeShowStatus').click(function() {
    if (showStatus === true) {
      $('#changeShowStatus i').removeClass('fa-eye-slash').addClass('fa-eye');
      $('#noShowPwd').addClass('none');
      $('#showPwd').removeClass('none');
      showStatus = false;
    } else {
      $('#changeShowStatus i').removeClass('fa-eye').addClass('fa-eye-slash');
      $('#noShowPwd').removeClass('none');
      $('#showPwd').addClass('none');
      showStatus = true;
    }
  })
})();