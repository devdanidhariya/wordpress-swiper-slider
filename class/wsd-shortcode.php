<?php
/**
 * use for add shortcode
 *
 * @author Devidas
 */

// check wither class exists or not
if( ! class_exists('wspShortcode') ) {
    class wspShortcode {
        
        public function slideshow_fun($atts, $content = null) {
            
            $atts = shortcode_atts( array(
		'id' => null
            ), $atts, 'slideshow' );
            
            $post_id =  $atts['id'];
            
            $post_type = get_post_type($post_id);
            if('wsd-slider' == $post_type){
                $total_slide = get_post_meta($post_id,'_slider_images',true);
                $attachment_ids = explode(',', $total_slide);

                $width = get_post_meta($post_id,'_slider_width',true);
                $height = get_post_meta($post_id,'_slider_height',true);

                $slider_config = get_post_meta($post_id,'_slider_config',true);
                $slider_config = json_decode($slider_config);

                $slidesperview = $slider_config->slidesPerView;
                $direction = $slider_config->direction;
                $spacebetween = $slider_config->spaceBetween;
                
                $delay = $slider_config->delay;
                $loop = $slider_config->loop;
                $type = $slider_config->type;
                $effect = $slider_config->effect;
                
                $uniqid = uniqid();
            }else{
                //return _e('Invalide shortcode argument. Or slider not exist','WSD');
                return;
            }
            

            ob_start();


        ?>
            <!-- Link Swiper's CSS -->
            <link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__); ?>../lib/css/swiper.min.css">

            <!-- Demo styles -->
            <style>
                .swiper<?php echo $uniqid; ?> {
                    <?php if($width){ ?>
                        width: <?php echo $width."px";?>;
                    <?php } ?>
                    height: <?php echo $height."px";?>;
                    margin-left: auto;
                    margin-right: auto;
                }
            </style>
            
            <!-- Swiper -->
            <div class="swiper-container swiper<?php echo $uniqid; ?>">
                <div class="swiper-wrapper">  
                    <?php 
                    if(!$attachment){

                    foreach ($attachment_ids as $aid) {



                         $image = wp_get_attachment_url( $aid);
                        if($image){ ?>

                            <div data-background="<?php echo $image; ?>" style="background-size:cover; background-position:center;" class="swiper-slide swiper-lazy">
                                <img src="<?php echo $image; ?>" class="img-responsive swiper-lazy" style="opacity:0;">
                                    <div class="swiper-lazy-preloader"></div>
                            </div>
                        <?php
                        }


                        /*
                        $image = wp_get_attachment_image( $aid, array($width, $height), "", array( "class" => "img-responsive swiper-lazy" ) );
                        
                        if($image){
                            echo '<div class="swiper-slide">';
                                echo $image;
                                echo '<div class="swiper-lazy-preloader swiper-lazy-preloader-white"></div>';
                            echo '</div>';
                        }

                        */
                          }
                    }      
                    ?>
                </div>
              <!-- Add Pagination -->
              <div class="swiper-pagination sp-<?php echo $uniqid; ?>"></div>
              <!-- Add Arrows -->
              <div class="swiper-button-next"></div>
              <div class="swiper-button-prev"></div>
            </div>
                    
            <!-- Swiper JS -->
            <script src="<?php echo plugin_dir_url(__FILE__); ?>../lib/js/swiper.min.js"></script>

            <!-- Initialize Swiper -->
            <script>
              var swiper = new Swiper('.swiper<?php echo $uniqid; ?>', {
                slidesPerView: <?php echo $slidesperview;?>,
                 // Enable lazy loading
                lazy: true,
                spaceBetween: <?php echo $spacebetween;?>,
                effect: '<?php echo $effect;?>',
                direction:'<?php echo $direction;?>',
                loop: <?php echo $loop;?>,
                autoplay: {
                    delay: '<?php echo $delay; ?>',
                    disableOnInteraction: false,
                  },
                pagination: {
                  el: '.sp-<?php echo $uniqid; ?>',
                  type: '<?php echo $type;?>',
                  clickable: true
                },
                navigation: {
                  nextEl: '.swiper-button-next',
                  prevEl: '.swiper-button-prev',
                },
              });
            </script>
            
           
        <?php

        $slider = ob_get_contents();
        ob_end_clean();

        return $slider;
        }
        
        
    }
}
