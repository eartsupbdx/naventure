<?php get_header(); ?>

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
         </div>
        </div>

       
      </div>

    </div>
  </div>

   

<?php get_footer(); ?>
  