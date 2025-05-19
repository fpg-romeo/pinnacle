$(document).ready(function () {
    $(document).on('click', 'button[name=subPost]', function(e){
        e.preventDefault();
       
        $('.required').remove();

        $('#post input, #post select, #post textarea').each(
            function(index){  
                var input   = $(this);
                var prop    = input.prop("required");
                var name    = input.prop("name");
                var type    = input.prop("type");
                var value   = input.val();
                var parent  = input.parent();

                if (typeof prop !== typeof undefined && prop !== false) {
                    if(value == ''){
                        parent.append('<i class="required">required field</i>');
                    }
                }
            }
        );

        if($('#post .required').length <= 0) {
            var tbody = $('.media-list');

            $.ajax({
                url: '/module/post_json/',
                type: 'POST',
                data: $('#post').serialize(),
                beforeSend: function(){

                },
                success: function(data){
                    $('#post input[name=latest_id]').val(data.latest_id);

                    var list = '';
                    if(typeof data.latest_post != "undefined" && data.latest_post != null && data.latest_post.length > 0){
                        $.each(data.latest_post, function(index, item) {

                            var heart = '';
                            var heart_total = '';
                            var comment_total = '';

                            if(item['heart'] == 1){
                                var heart = ' tx-danger ';
                            }

                            if(item['heart_total'] > 0){
                                var heart_total = item['heart_total'];
                            }

                            if(item['comment_total'] > 0){
                                var comment_total = item['comment_total'];
                            }

                            list += '<div class="media pd-20 pd-xs-20 mg-b-20 bg-white shadow-base latestPost" data-id="'+item['id']+'"><img src="/file/account/'+item['account_photo']+'" alt="" class="wd-40 rounded-circle"><div class="media-body mg-l-20"><div class="d-flex justify-content-between mg-b-10"><div><h6 class="mg-b-2 tx-inverse tx-14">'+item['account_first_name']+'</h6><span class="tx-12 tx-gray-500">'+item['department_name']+'</span></div><span class="tx-11 tx-gray-500">'+item['created_when']+'</span></div><p class="mg-b-20">'+item['content']+'</p><div class="media-footer"><div><a href=""><i class="fa fa-heart heart heart-'+item['id']+' '+heart+'" data-postid="'+item['id']+'"></i>&nbsp;<small class="tx-11 heart-total-'+item['id']+'">'+heart_total+'</small></a><a class="mg-l-10"><i class="fa fa-comment comment" data-postid="'+item['id']+'"></i>&nbsp;<small class="tx-11 comment-total-'+item['id']+'">'+comment_total+'</small></a></div></div><div id="" class="comment-block comment-block-'+item['id']+'"><div class="row"><div class="col-lg-12"><div class="card bg-gray-100 widget-5 pd-10"><div class="list-group-'+item['id']+'"></div><div class="card-footer pd-x-0 pd-y-0"><textarea class="form-control content content-'+item['id']+' no-resize tx-12" rows="1" draggable="false" placeholder="Comment. . ." required data-postid="'+item['id']+'"></textarea></div></div></div></div></div></div></div>';
                        });    
                        $(tbody).prepend(list);
                    }
                },
                complete: function() {
                    $('#post textarea[name=content]').val('');
                },
                error: function(xhr, desc, err){ 
                    //console.log(xhr);
                    console.warn(xhr.responseText);
                }
           });
        }
    });
});

$(window).load(function(){  
    $('.latestPost').each(function() {
        var latest_id = $('.latestPost').attr('data-id');
        $('input[name=latest_id]').val(latest_id);
    });
});

$(document).ready(function () {
    $(document).on("click", ".heart", function (e) {
        e.preventDefault();
       
        var post_id     = $(this).data('postid');
        var category_id = $('input[name=module_category_id]').val();
        var article_id  = $('input[name=module_article_id]').val();

        $.ajax({
            url: '/module/heart_json/',
            type: 'POST',
            data: {action:'heart', category_id:category_id, article_id:article_id, post_id:post_id},
            beforeSend: function(){

            },
            success: function(data){
                var heart_total = '';
                if(data.heart_total > 0){
                    heart_total = data.heart_total; 
                }
                $('.heart-total-'+post_id).html(heart_total);

                if(data.action == 'Save'){
                    $('.heart-'+post_id).addClass('tx-danger');
                }else{
                    $('.heart-'+post_id).removeClass('tx-danger');
                }
            },
            complete: function() {
                $('#post textarea[name=content]').val('');
            },
            error: function(xhr, desc, err){ 
                //console.log(xhr);
                console.warn(xhr.responseText);
            }
        });
    });
});

$(document).ready(function () {
    $(document).on("click", ".comment", function (e) {
        e.preventDefault();
       
        var post_id = $(this).data('postid');

        $('.comment-block textarea.content').val('');
        $('.comment-block-'+post_id).slideToggle("slow");
    });

    $(document).on("keypress", ".comment-block .content", function (e) {
        //e.preventDefault();

        if(e.which == 13 && content != ''){ 

            var post_id     = $(this).data('postid');
            var category_id = $('input[name=module_category_id]').val();
            var article_id  = $('input[name=module_article_id]').val();
            var content     = $(this).val();
            var tbody       = $('.list-group-'+post_id);

            $.ajax({
                url: '/module/comment_json/',
                type: 'POST',
                data: {action:'post', category_id:category_id, article_id:article_id, post_id:post_id, content:content},
                beforeSend: function(){

                },
                success: function(data){
                    $('.comment-total-'+post_id).html(data.comment_total);

                    var list = '';
                    if(typeof data.comment_latest != "undefined" && data.comment_latest != null && data.comment_latest.length > 0){
                        $.each(data.comment_latest, function(index, item) {
                            list += '<div class="list-group list-group-flush bd-b-0"><div class="list-group-item list-group-item-action media"><img src="/file/account/'+item['account_photo']+'" alt=""><div class="media-body"><div class="msg-top"><span>'+item['account_first_name']+' '+item['account_last_name']+'</span><span>'+item['created_when']+'</span></div><p class="msg-summary">'+item['content']+'</p></div></div></div>';
                        });   
                        $(tbody).last().append(list); 
                    }
                },
                complete: function() {
                    $('textarea.content-'+post_id).val('');
                },
                error: function(xhr, desc, err){ 
                    //console.log(xhr);
                    console.warn(xhr.responseText);
                }
            });

            return false;

        }
    });
});