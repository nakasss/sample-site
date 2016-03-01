<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
                    
                        <div class="site-branding">
                            <h1 class="site-title"><a href="<?php echo get_admin_url(); ?>" rel="home"><img src="<?php echo get_bloginfo('template_directory').'/images/Scopic_VR_Amsterdam.png'; ?>"></a></h1>
                        </div><!-- .site-branding -->
		<?php 
		?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->
        
<?php get_footer(); ?>
