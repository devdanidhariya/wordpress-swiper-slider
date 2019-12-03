<?php

/**
 * Manage admin area functinality
 *
 * @author Devidas
 */
// check wither class exists or not
if( ! class_exists('adminSlider') ) :
    
    class adminSlider {

        public function __construct() {
            
        }
        public function slider_form_submit() {
            
            $slider_images = $_REQUEST['attachment_ids'];
            parse_str($_REQUEST['config'],$config);
            $post_id = $config['id']; 
            $width = $config['width'];
            $height = $config['height'];
            
            $my_post =  array(
                            'ID' =>$post_id,
                            'post_title'    => wp_strip_all_tags( $config['slider-name'] ),
                            'post_status'   => 'publish',
                            'post_type'  =>  'wsd-slider'
                        );
            $post_id = wp_insert_post($my_post);
            
            $config_array = array(
                                "direction"=>$config['slider-type'] ?: 'horizontal',
                                "spaceBetween" => $config["spaceBetween"] ?: '30',
                                "slidesPerView" => $config["slidesPerView"]?:'1',
                                "delay" => $config["delay"]?:'2500',
                                "loop" => $config["loop"]?:'true',
                                "type" => $config["pagination-type"]?:'bullets',
                                "effect" => $config["effect"]?:'slide'
                            );
            
            $config = json_encode($config_array);
                
            //Slider  meta
            update_post_meta( $post_id, '_slider_images', $slider_images); 
            update_post_meta( $post_id, '_slider_config', $config);
            update_post_meta( $post_id, '_slider_width', $width);
            update_post_meta( $post_id, '_slider_height', $height);
            
            echo $post_id;
            
            wp_die(); // this is required to terminate immediately and return a proper response
        }

        public function addSlider(){ 
            
            if(isset($_REQUEST['id']) && $_REQUEST['id'] >=1){
                $post_id = $_REQUEST['id'];
                
                $post_type = get_post_type($post_id);
                 if('wsd-slider' == $post_type){
                    $post_title = get_the_title($post_id);
                     
                    $total_slide = get_post_meta($post_id,'_slider_images',true);
                    $total_slide = explode(',', $total_slide);
                    
                    $width = get_post_meta($post_id,'_slider_width',true);
                    $height = get_post_meta($post_id,'_slider_height',true);
                    
                    $slider_config = get_post_meta($post_id,'_slider_config',true);
                    $slider_config = json_decode($slider_config);
                    
                    $direction = $slider_config->direction;
                    $spacebetween = $slider_config->spaceBetween;
                    $slidesperview = $slider_config->slidesPerView;
                    $delay = $slider_config->delay;
                    $loop = $slider_config->loop;
                    $type = $slider_config->type;
                    $effect = $slider_config->effect;
                    
                    
                 }
            }
            
            $slider_display_url = admin_url('/admin.php?page=slide-short-code-list');
            ?>
            <style>
                img.shortable-img{
                    cursor: move;
                }
            </style>
            <div class="container">
                
                <div class="mt-4"></div>    
                <div class="row">
                    <div class="col-12">
                        <a class="btn btn-info" href="<?php echo $slider_display_url; ?>"><i class="fa fa-home" ></i>&nbsp;Home</a>
                    </div>
                </div>
                <div class="mt-2"></div>    
                <form action="" method="post" name="wsd-setting" id="wsd-setting" class="">
                    <input type="hidden" value="<?php echo $post_id;?>" name="id" id="slider_id" />
                <div class="panel-group" id="accordion">

                    <div class="panel panel-default">

                       <div class="panel-heading card-header text-center">
                          <h4 class="panel-title">
                              <a data-toggle="collapse" data-parent="#accordion" href="#collapse1"><i class="fa fa-cog" aria-hidden="true"></i>&nbsp;Configurations</a>
                          </h4>
                       </div>

                       <div id="collapse1" class="panel-collapse collapse in show">
                          <div class="panel-body">
                            <div class="mt-4"></div>  
                            <div class="row">  
                                <div class="form-group col-md-3">
                                    <label for="slider-name">Slider Name</label>
                                    <input name="slider-name" value="<?php echo $post_title; ?>" type="text" class="form-control" id="slider-name" required>
                                </div>
                                <div class="form-group col-md-3">
                                   <label for="slider-type">Slider Type</label>
                                   <select id="slider-type" name="slider-type" class="form-control">
                                        <option value="horizontal" <?php if($direction == 'horizontal'){ echo "selected"; } ?> >Horizontal</option>
                                        <option value="vertical" <?php if($direction == 'vertical'){ echo "selected"; } ?> >Vertical</option>
                                   </select>
                                   <small class="form-text text-muted">Default <span class="font-weight-bold text-success">Horizontal</span></small>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="height">Height</label>
                                    <div class="input-group mb-2">
                                        <input type="text" value="<?php echo $height;?>" class="form-control" name="height" id="height" required>
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">px</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="width">Width</label>
                                    <div class="input-group mb-2">
                                      <input type="text" value="<?php echo $width;?>" class="form-control" name="width" id="width" >
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">px</div>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted font-italic">Leave blank. If you set width then it may be<span class="text-danger text-capitalize"> affected in responsive design</span>. Default take parent element height</small>
                                </div>
                            </div>  
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="spaceBetween">Space Between</label>
                                    <div class="input-group mb-2">
                                        <input type="text" value="<?php echo $spacebetween;?>" class="form-control" name="spaceBetween" id="spaceBetween">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">px</div>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted font-italic">Distance between slides</small>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="delay">Delay</label>
                                    <input type="number" value="<?php echo $delay;?>" min="2500" step="100" max="8000" class="form-control" name="delay" id="delay">
                                    <small id="emailHelp" class="form-text text-muted font-italic">Duration of transition between slides (<span class="font-weight-bold text-success">in ms</span>)</small>
                                </div>
                                <div class="form-group col-md-3">
                                   <label for="loop">Loop</label>
                                   <select id="loop" name="loop" class="form-control">
                                      <option value="true" <?php if($loop == 'true'){ echo "selected"; } ?>>True</option>
                                      <option value="false" <?php if($loop == 'false'){ echo "selected"; } ?>>False</option>
                                   </select>
                                   <small class="form-text text-muted ">Default <span class="font-weight-bold text-success">True</span><br>(<span class="font-italic">Set to true to enable continuous loop mode</span>)</small>
                                </div>
                                 <div class="form-group col-md-3">
                                   <label for="pagination-type">Pagination Type</label>
                                   <select id="pagination-type" name="pagination-type" class="form-control">
                                      <option value="bullets" <?php if($type == 'bullets'){ echo "selected"; } ?> >Bullets</option>
                                      <option value="fraction" <?php if($type == 'fraction'){ echo "selected"; } ?>>Fraction</option>
                                      <option value="progressbar" <?php if($type == 'progressbar'){ echo "selected"; } ?>>Progressbar</option>
                                   </select>
                                </div>
                            </div>
                            <div class="row">
                                
                                <div class="form-group col-md-3">
                                    <label for="effect">Effect</label>
                                    <select id="effect" name="effect" class="form-control">
                                       <option value="slide" <?php if($effect == 'slide'){ echo "selected"; } ?>>Slide</option>
                                       <option value="fade" <?php if($effect == 'fade'){ echo "selected"; } ?>>Fade</option>
                                       <option value="cube" <?php if($effect == 'cube'){ echo "selected"; } ?>>Cube</option>
                                    </select>
                                </div>
                            </div>
                          </div>
                       </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading card-header text-center">
                           <h4 class="panel-title">
                               <a data-toggle="collapse" data-parent="#accordion" href="#collapse2"><i class="fa fa-camera-retro" aria-hidden="true"></i>&nbsp;Add Slide</a>
                           </h4>
                        </div>
                        <div id="collapse2" class="panel-collapse collapse">
                           <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="sortable" class="d-flex flex-center flex-wrap">
                                            <?php 
                                             
                                                if(!empty($total_slide)){
                                                    foreach ($total_slide as $attachment_id) { 
                                                        $thumbnail_url = wp_get_attachment_image_src($attachment_id,'thumbnail');
                                                    ?>

                                                     <div class="col-sm-3 card-wrapper">
                                                        <div class="card">
                                                            <span class="pull-right clickable close-icon" data-effect="fadeOut"><i class="fa fa-times close pull-right" aria-hidden="true" style="color:red"></i></span>
                                                            <div class="card-block">
                                                                <img class="card-img-top img-fluid shortable-img" src="<?php echo $thumbnail_url[0];?>" data-id="<?php echo $attachment_id;?>" alt="Card image cap">
                                                            </div>
                                                        </div>
                                                    </div>       
                                        <?php } } ?>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="row  mt-5">
                                    <div class="col-10">
                                        <div class="text-center">
                                        <a href="#" id="add-slide-btn" class="btn  btn-lg btn-primary text-center">
                                            <i class="fa fa-camera-retro fa-2x  pull-left" style=" vertical-align: middle;"></i>
                                            <span>&nbsp;Add Slide</span>
                                        </a>
                                        </div>
                                    </div>
                                </div>
                               <div class="mb-5"></div>
                           </div>
                        </div>
                    </div>
                    
                    
                    
                    <?php  if(isset($_REQUEST['id']) && $_REQUEST['id'] >=1){ ?>
                    
                    <div class="panel panel-default">
                        <div class="panel-heading card-header text-center">
                           <h4 class="panel-title">
                               <a data-toggle="collapse" data-parent="#accordion" href="#collapse4"><i class="fa fa-newspaper" aria-hidden="true"></i>&nbsp;Slider Preview</a>
                           </h4>
                        </div>
                        <div id="collapse4" class="panel-collapse collapse">
                           <div class="panel-body">
                               <div class="row mt-5">
                                    <div class="col-12">
                                        <div class="swiper-preview-div"></div>
                                    </div>
                                </div>
                                <div class="row mt-3"> 
                                    <div class="col-12">
                                        
                                        <div class="text-center">
                                            <button type="button" class="btn btn-outline-primary shortcode-preview" data-post_id="<?php echo $post_id ?>"><i class="fa fa-newspaper"></i> Preview</button>
                                        </div>
                                    </div>
                                </div>
                               <div class="mb-5"></div>
                           </div>
                        </div>
                    </div>
                    
                        <?php } ?>
                    <div class="panel panel-default">
                       <div class="panel-heading card-header text-center">
                          <h4 class="panel-title">
                             <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">Action</a>
                          </h4>
                       </div>
                       <div id="collapse3" class="panel-collapse collapse">
                            <div class="panel-body">
                                
                                <div class="row mt-5">
                                    <div class="col-6">
                                        <div class="text-center">
                                            <button class="btn btn-success" id="slider-submit" type="submit">Save Slider</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <div class="col-12 response-msg">
                                        
                                    </div>
                                </div>   
                            </div>
                       </div>
                    </div>
                </div>  
                
                </form>
            </div>                
        <?php
        }

        /*
        *  displayShortcodeList
        *
        *  Display list of shortcode.
        *  @type	function
        *
        *  @param	N/A
        *  @return	N/A
        */

        public function displayShortcodeList(){ 
            $add_slide_url = admin_url('/admin.php?page=add-slider');
            ?>

            <div class="container">
                <div class="row mt-5"></div>
                
                
                <div class="row">
                    <div class="col-12">
                        <div class="swiper-preview-div"></div>        
                    </div>
                </div>
                
                <div class="row mt-5">
                    <div class="col-2">
                        <a class="btn btn-success" href="<?php echo $add_slide_url; ?>"><i class="fa fa-plus" ></i>&nbsp;New Slider</a>
                    </div>

                </div>

                
                <div class="row mt-5">
                    <div class="col-md-12">
                        <table id="shortcode-list" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Shortcode</th>
                                    <th>Copy Shortcode</th>
                                    <th>Preview</th>
                                    <th>Total Slide</th>
                                    <th>Width x Height  (px)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                
                                $args = array(
                                        'post_type'=> 'wsd-slider',
                                        'post_status'    => 'any',
                                        'order'    => 'DESC'
                                        );              

                                    $the_query = new WP_Query( $args );
                                    
                                    if($the_query->have_posts() ) : 
                                        while ( $the_query->have_posts() ) :
                                            $the_query->the_post();
                                            $post_id = get_the_id();
                                            $total_slide = get_post_meta($post_id,'_slider_images',true);
                                            $total_slide = explode(',', $total_slide);
                                            $width = get_post_meta($post_id,'_slider_width',true)?:"Defualt";
                                            $height = get_post_meta($post_id,'_slider_height',true);

                                            ?>
                                            <tr>
                                                <td><?php the_title(); ?></td>
                                                <td>[slideshow id="<?php echo $post_id; ?>" /]</td>
                                                <td><button class="btn btn-outline-warning copy-shortcode" data-shortcode='[slideshow id="<?php echo $post_id; ?>" /]' ><i class="fa fa-paste"></i> <span>Copy</span></button></td>
                                                <td><button class="btn btn-outline-primary shortcode-preview" data-post_id="<?php echo $post_id ?>"><i class="fa fa-newspaper"></i> Preview</button></td>
                                                <td><?php echo count($total_slide); ?></td>
                                                <td><?php echo "$width x $height";?></td>
                                                <td>
                                                    <a class="btn btn-info" href="<?php echo $add_slide_url."&id=$post_id";?>"><i class="fa fa-pencil-alt" aria-hidden="true"></i> Edit</a>
                                                    <div class="mt-1"></div>
                                                    <button class="btn btn-danger delete-shortcode" data-post_id="<?php echo $post_id ?>"><i class="fa fa-trash"></i> Trash</button>
                                                </td>
                                            </tr>
                                        <?php
                                        endwhile;
                                    endif;    

                                    ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Name</th>
                                    <th>Shortcode</th>
                                    <th>Copy Shortcode</th>
                                    <th>Preview</th>
                                    <th>Total Slide</th>
                                    <th>Width x Height (px)</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        <?php
        }
        
        public function slider_delete(){
            wp_delete_post( $_REQUEST['post_id'], true );
            wp_die(); // this is required to terminate immediately and return a proper response
        }
        
        public function slider_preview() { 
            
            $post_id = $_REQUEST['post_id'];
            $short_code = "[slideshow id='$post_id' ]";
            ?>
            <div class="text-center text-primary">
                <h4>Slider Preview</h4>
                <button type="button" class="btn btn-danger btn-denger m-0 mb-1 mt-1 preview-close"><i class="fa fa-times"></i>&nbsp;Close</button>
            </div>
            
            <?php
            
            echo  do_shortcode($short_code);
        
            wp_die(); // this is required to terminate immediately and return a proper response
        } 
        
        
        // hooks your functions into the correct filters
        public function wsd_add_mce_button() {
            // check user permissions
            if ( !current_user_can( 'edit_posts' ) &&  !current_user_can( 'edit_pages' ) ) {
                return;
            }
            
            // check if WYSIWYG is enabled
            if ( 'true' == get_user_option( 'rich_editing' ) ) {
               add_filter( 'mce_external_plugins', array('adminSlider','wsd_add_tinymce_plugin') );
               add_filter( 'mce_buttons', array('adminSlider','wsd_register_mce_button') );
            }
        }


        // register new button in the editor
        public function wsd_register_mce_button( $buttons ) {
            array_push( $buttons, 'wsd_mce_button' );
            return $buttons;
        }


        // declare a script for the new button
        // the script will insert the shortcode on the click event
        public function wsd_add_tinymce_plugin( $plugin_array ) {
                  $plugin_array['wsd_mce_button'] = plugin_dir_url(__FILE__).'../lib/js/mce-button.js';
                  return $plugin_array;
        }
        
        public function add_html_in_footer() { ?>
            <style>
                
                .wsd-shortcode-insert {
                    -webkit-box-shadow:  0px 0px 0px 9999px rgba(0, 0, 0, 0.5);
                    display: none;
                    box-shadow:  0px 0px 0px 9999px rgba(0, 0, 0, 0.5);
                    position: fixed;
                    padding: 10px;
                    left: 35%;
                    top: 30%;
                    background: #FFF;
                    z-index: 9999;
                    width: 35%;
                }
                #wsd-shortcode-popup-close{
                    float: right;
                    color: red;
                    cursor: context-menu !important;
                }
                .wsd-title{
                    text-align: center;
                }
                
                
                div.mce-swiper-slider-btn button{
                    color: white;
                    border-radius: 4px;
                    text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
                    background: #0085ba;  
                    text-decoration:none;
                    font-size: 110%;
                        
                }
                .gap-30{
                    margin: 30px;
                }
                
            </style>
            
            <div class="wsd-shortcode-insert" id="wsd-shortcode-insert">
                <div class="wsd-title">
                    <h3>Select slideshow to insert into post<span id="wsd-shortcode-popup-close">X</span></h3>
                </div><hr>
                <div class="gap-30"></div>
                        <?php
                            $args = array(
                                        'post_type'=> 'wsd-slider',
                                        'order'    => 'DESC'
                                    );              

                                $the_query = new WP_Query( $args );

                                if($the_query->have_posts() ) { ?>
                                    <div class="wsd-slider-select">
                                        <label class="post-attributes-label" for="start_of_week">Slider Name</label>
                                        <select id="start_of_week" name="start_of_week">
                                    
                                    <?php
                                    while ( $the_query->have_posts() ) {
                                        $the_query->the_post();
                                        $post_id = get_the_id();
                                        echo "<option value='$post_id'>".get_the_title($post_id)."</option>";
                                    } ?>
                                        </select>
                                        <input type="button" name="wsd-insert-shortcode" id="wsd-insert-shortcode" class="button button-primary button-large" value="Insert Shortcode" />
                                    </div>
                                <?php
                                }else{
                                    echo "<h3>Please add at least one slideshow</h3>";
                                } 
                                ?>
                <div class="gap-30"></div>
            </div>


<?php
        }
        
    }
    
    new adminSlider();
    
endif;   