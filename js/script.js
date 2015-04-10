// Generated by CoffeeScript 1.8.0
(function() {
  $(function() {
    var $mf, update_cal;
    $(document).foundation;
    $mf = $('#month-field');
    update_cal = function() {
      var m;
      m = $('#m').val();
      if (0 === parseInt(m)) {
        $mf.children('table').css('opacity', '0.25');
        $mf.children('.switch.day').addClass('disabled');
        return;
      }
      $mf.show();
      return $.ajax({
        type: 'GET',
        url: './cal.php',
        data: {
          'y': $('#y').val(),
          'm': m
        },
        dataType: 'html',
        success: function(data) {
          return $mf.html(data);
        },
        error: function() {
          return $mf.html('カレンダーのロードに失敗しました');
        }
      });
    };
    $('#y').change(update_cal);
    return $('#m').change(update_cal);
  });

}).call(this);