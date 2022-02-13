
(function($){
      $('#like-form').on('submit',function(e){
          e.preventDefault();
            
          var btn = $(this).find('button');
          
          btn.html(like_post.wait).prop('disabled',true);
           
          var data = $(this).serialize();
          $.post(like_post.ajax_url, data, function(response) {
            alert(like_post.thank_you);
            btn.html(like_post.like).prop('disabled',false);
            $('#like_value').html(`${response.likes}`);
        })
      })
      // dislike button
      $('#dislike-form').on('submit',function(e){
        e.preventDefault();
        var btn = $(this).find('button');
          
        btn.html(like_post.wait).prop('disabled',true);
        var data = $(this).serialize();
        $.post(like_post.ajax_url, data, function(response) {
            alert(like_post.thank_you);
            btn.html(like_post.dislike).prop('disabled',false);
            $('#dislike_value').html(`${response.dislikes}`)

        })
        
    })

})(jQuery);
