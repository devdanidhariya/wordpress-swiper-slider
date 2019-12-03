jQuery(function($) {
    $(document).ready(function() {
         
        $("#sortable").sortable();
        $("#sortable").disableSelection();
        
        
        $('#shortcode-list').DataTable(
            {
                "order": [[ 1, "desc" ]],
                responsive: true
            }
        );
        //call media open function
        $("#add-slide-btn").click(function(e) {
            
            e.preventDefault();
            var frame;
            
            // If the media frame already exists, reopen it.
            if (frame) {
                frame.open();
                return;
            }

            // Create a new media frame
            frame = wp.media({
                title: 'Swiper Slider',
                button: {
                    text: 'Add Slide'
                },
                multiple: true // Set to true to allow multiple files to be selected
            });


            // When an image is selected in the media frame...
            frame.on('select', function() {

                // Get media attachment details from the frame state
                var attachment = frame.state().get('selection').toJSON();
                $.each(attachment, function(key, value) {



         var html = '<div class="col-sm-3 card-wrapper">\
                        <div class="card">\
                            <span class="pull-right clickable close-icon" data-effect="fadeOut"><i class="fa fa-times close pull-right" aria-hidden="true" style="color:red"></i></span>\
                            <div class="card-block">\
                                <img class="card-img-top img-fluid shortable-img" src="' + value.url + '" data-id="' + value.id + '" alt="Card image cap">\
                            </div>\
                        </div>\
                    </div>';
                    
                    $('div#sortable').append(html);
                });
            });

            // Finally, open the modal on click
            frame.open();
        });

        $("body").on("click", ".close", function() {
            $.when(
                $(this).parents('div.card').fadeOut("slow").promise().done(
                    function() {
                        $(this).parents('div.card-wrapper').remove();
                    })
            );
        });
        
        
        $("body").on("click", ".preview-close", function() {
            $(this).parents('div.swiper-preview-div').empty()
        });
        
        
        $("button.shortcode-preview").on("click",function() {
            $.ajax({
                url: ajaxurl,
                data: {
                    action: 'slider_preview',
                    post_id:$(this).data('post_id')
                },
                success:function(data) {
                    $('.swiper-preview-div').empty().html(data);
                    $('#slider-popup').modal('show');
                }
            });
        });
        $("button.copy-shortcode").on("click",function() {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(this).data('shortcode')).select();
            document.execCommand("copy");
            $temp.remove();
            $(this).find('span').text('Coped!');
            $(this).removeClass('btn-outline-warning');
            $(this).addClass('btn-warning');
        });
        $("button.delete-shortcode").on("click",function() {
            $_this =  this;
            var confirm_status = confirm("Are you sure to Delete?");
            if (confirm_status) {
                $.ajax({
                    url: ajaxurl,
                    data: {
                        action: 'slider_delete',
                        post_id:$(this).data('post_id')
                    },
                    success:function(data) {
                        $($_this).closest("tr").remove();
                        //document.location.reload(true);
                    },
                    error: function(errorThrown){
                        console.log(errorThrown);
                    }
                });
            }
        });
        
        $("form#wsd-setting").on("submit",function() {
            event.preventDefault();
            var config = $("form#wsd-setting").serialize();
            var attachment_ids = [];
            $( "div.card-wrapper" ).each(function() {
                attachment_ids.push($( this ).find('img.shortable-img').data('id'));
            });
            
            var attachment_ids = attachment_ids.join();
            if(attachment_ids == ''){
                alert('Please add at least 1 image');
                return false;
            }
            $.ajax({
                url: ajaxurl,
                data: {
                    action: 'slider_form_submit',
                    attachment_ids:attachment_ids,
                    config:  config
                },
                success:function(data) {
                    $("#slider_id").val(data);
                    var alert = '<div class="alert alert-success" role="alert">\
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>\
                                    <strong>Success!</strong> Your record saved successfully!\
                                </div>';
                    
                    $('.response-msg').empty().html(alert);
                    setTimeout(function() {
                        $(".alert").alert('close');
                        window.location.href = '/wp-admin/admin.php?page=add-slider&id='+data;
                    }, 2000);
                    

                    
                },
                error: function(errorThrown){
                    console.log(errorThrown);
                }
            });
            return false;
        });
        
    });
    
   
});