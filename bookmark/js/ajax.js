$(window).on('load', function () {
  $('.js-button').on('click', function () {
    $('button').attr('disabled', true);
    var input_value = new FormData($('#post_form')[0]);
    $.ajax({
      type: 'post',
      url: '/bookmark/ajax_post.php',
      data: input_value,
      dataType: 'json',
      processData: false,
      contentType: false
    }).done(function (response, textStatus, xhr) {
      alert('done');
      console.log(response);
      var str = JSON.stringify(response)
      $('.js-visible').html(str)
    }).fail(function (response, textStatus, xhr) {
      alert('fail');
      console.log(response);
      return false;
    }).always(function () {
      $('.input').val('');
      $('.textarea').val('');
      $('button').attr('disabled', false);
    });
  });
});