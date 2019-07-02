<?php get_header(); ?>
<style>
    p a{
        text-decoration: none;
        color: #000;
    }
    p a:hover{
        text-decoration: underline;
    }
</style>
<div class="container-fluid" style="text-align: center;">
<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/logo-1.jpg" height="120" style="padding-top: 15px; padding-bottom: 10px;">
    </div>


  <div class="container">
    <div class="section">

      <!--   Icon Section   -->
      <div class="row">
        <div class="col s12 m12">
        <div style="min-height:550px;">
            <?php
            if (have_posts()) :
   while (have_posts()) :
      the_post();
         
   
            ?>
            
            <h2 class="center"><?php echo get_the_title(); ?></h2>
            <?php the_content(); ?>
<?php
            endwhile;
endif;
            ?>
            <div class="row">
            <?php
            $blogusers = get_users( 'orderby=nicename' );
// Array of WP_User objects.
foreach ( $blogusers as $user ) {
	echo '<div class="col s12 m12"><h5>Collection de ' . esc_html( $user->user_nicename ) . '</h5>';
    $args = array(
	   'author'      => $user->ID,
	   'post_status' => 'any',
	   'post_type'   => 'attachment',
		'post_parent' => 0,
		'posts_per_page' => -1
	);
	$pics_query = new WP_Query( $args );
    
    if( count($pics_query->posts) == 0 ){
        echo esc_html( $user->user_nicename ) . " n'a pas encore d'images dans sa collection !";
    }
    
	//print_r( $pics_query );
	foreach( $pics_query->posts as $pic ){
        echo '<div style="float:left; margin-right:10px; width:20%; min-height:280px;"><img src="'.$pic->guid.'" width="250" height="180"/>';
        $arr = explode(', ', $pic->post_title);
         ?> 
        
            	<p style="margin-top: 0px; line-height: normal; margin-bottom: 0px;"><?php foreach( $arr as $value ){ echo '<a href="https://www.google.com/search?q='.$value.'" target="_blank">'.$value.'</a>, '; }; ?></p>
            </div>
                <?php
        //'<small>'.$pic->post_title.'</small></div>';//print_r($pic);
    }
    echo '</div>';
}
            ?>
            </div>
         </div>
        </div>

       
      </div>

    </div>
  </div>

   

<?php get_footer(); ?>
  