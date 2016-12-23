$(document).ready(function(){
  $('a').on('mousedown', stopNavigate);
  $('a').on('mouseleave', function () {
    $(window).on('beforeunload', function(){
      $.ajax({
        type : 'POST',
        url  : 'logout.php'
      });
    });
  });
});

function stopNavigate(){
  $(window).off('beforeunload');
}

$(window).on('beforeunload', function(){
  $.ajax({
    type : 'POST',
    url  : 'logout.php'
  });
});

$(window).on('unload', function(){
  $.ajax({
    type : 'POST',
    url  : 'logout.php'
  });
});

var idleTime = 0;
$(document).ready(function () {
    //var idleInterval = setInterval(timerIncrement, 60000);
    var idleInterval = setInterval(timerIncrement, 60000);
    $(this).mousemove(function (e) {
        idleTime = 0;
    });
    $(this).keypress(function (e) {
        idleTime = 0;
    });
});

function timerIncrement() {
    idleTime = idleTime + 1;
    if (idleTime > 1) {
      $.ajax({
        type : 'POST',
        url  : 'logout.php'
      });
      window.location.reload();
    }
}
