<?php
/*
Template Name: profil Page
*/
the_post();
get_header();
if ( is_user_logged_in() ) {
	$current_user = wp_get_current_user();
    ?>

<div class="container-fluid" style="text-align: center;">
<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/logo-1.jpg" height="120" style="padding-top: 15px; padding-bottom: 10px;">
    </div>


  <div class="container">
    <div class="section">

      <!--   Icon Section   -->
      <div class="row">
        <div class="col s12 m12">
        <div>
        <h2 class="center"><?php echo get_the_title(); ?></h2>
		<?php
	
	echo 'Pseudo: ' . $current_user->user_login . '<br />';
    echo 'Email: ' . $current_user->user_email . '<br />';
    //echo 'Prénom: ' . $current_user->user_firstname . '<br />';
    //echo 'Nom: ' . $current_user->user_lastname . '<br />';
    //echo 'User display name: ' . $current_user->display_name . '<br />';
    //echo 'User ID: ' . $current_user->ID . '<br />';
	
	$user_api_key = get_user_meta( $current_user->ID, 'api_key', true ); 
	echo 'Clé API: ' . $user_api_key;
		?>
	</div>
	</div>
	<div class="col s12 m12" >
		<h2 class="center">Ma collection</h2>
		<div class="picsmosaic" style="min-height: 550px;">
	<?php
	$args = array(
	   'author'      => $current_user->ID,
	   'post_status' => 'any',
	   'post_type'   => 'attachment',
		'post_parent' => 0,
		'posts_per_page' => -1
	);
	$pics_query = new WP_Query( $args );
	//print_r( $pics_query );
    
    if( count($pics_query->posts) == 0 ){
        echo "tu n'as pas encore d'images dans sa collection !";
    }
    
	foreach( $pics_query->posts as $pic ){
		/*
		[ID] => 39 [post_author] => 1 [post_date] => 2018-05-05 11:19:27 [post_date_gmt] => 2018-05-05 09:19:27 [post_content] => [post_title] => image [post_excerpt] => [post_status] => inherit [comment_status] => open [ping_status] => closed [post_password] => [post_name] => image-3 [to_ping] => [pinged] => [post_modified] => 2018-05-05 11:19:27 [post_modified_gmt] => 2018-05-05 09:19:27 [post_content_filtered] => [post_parent] => 0 [guid] => http://www.citybirds.info/wp-content/uploads/2018/05/image-2.jpg [menu_order] => 0 [post_type] => attachment [post_mime_type] => image/jpeg [comment_count] => 0 [filter] => raw
		*/
		?>
			<div class="pic" style="width: 20%; float: left; margin-right: 10px;">
				<img width="100%" height="160" src="<?php echo $pic->guid; ?>"/>
				    <?php $arr = explode(', ', $pic->post_title); ?>
                <small><?php echo $pic->post_date; ?></small>
            	<p style="margin-top: 5px; line-height: normal;"><?php foreach( $arr as $value ){ echo '<a href="https://www.google.com/search?q='.$value.'" target="_blank">'.$value.'</a>, '; }; ?></p>
					
				
			</div>
		<?php
	}
	?>
         </div>
        </div>
<style>
    .pic a{
        text-decoration: none;
    }
    .pic a:hover{
        text-decoration: underline;
    }
          </style>
       
      

    </div>
  </div>
</div>
   


<?php
} else {
}
?>

<?php get_footer() ?>