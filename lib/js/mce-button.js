(function() {
    tinymce.PluginManager.add('wsd_mce_button', function( editor, url ) {
        editor.addButton('wsd_mce_button', {
            title: 'Add Swiper Slider' ,
            icon: false,
            text: 'Swiper Slider',
            classes: ' swiper-slider-btn',
            onclick: function() {
                jQuery("#wsd-shortcode-insert").css("display","block");
            }
        });
    });
    
    
    jQuery("#wsd-shortcode-popup-close").on("click",function() {
        jQuery("#wsd-shortcode-insert").css("display","none");
    });
    
    jQuery("#wsd-insert-shortcode").on("click",function() {
        var id = jQuery("#start_of_week").val();
        if(id){
            window.send_to_editor('[slideshow id="' + id + '"/]');
            jQuery("#wsd-shortcode-insert").css("display","none");
        }

    });
    
})();