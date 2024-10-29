<?php 
$max_blogs = get_option('blog_limit');
if( $_POST['alpenglo_hidden'] == 'Y' ) {
        // Read their posted value
        $max_blogs = $_POST['blog_limit'];
		  // Save the posted value in the database
        update_option('blog_limit', $max_blogs );
        // Put an options updated message on the screen

?>
<div class="updated"><p><strong><?php _e('Options saved.', 'alpenglo_admin' ); ?></strong></p></div>
<?php

    }

if (!isset($max_blogs)){$max_blogs= 5;
	}	?>

		<div class="wrap">
			<?php    echo "<h2>" . __( 'AlpenGlo Preferences', 'blog_limit' ) . "</h2>"; ?>
			
			<form name="alpenglo_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
				<input type="hidden" name="alpenglo_hidden" value="Y">
				<?php    echo "<h4>" . __( 'Settings', 'blog_limit' ) . "</h4>"; ?>
				<p><?php _e("Number of related blogs to show at the end of each post: " ); ?><input type="text" name="blog_limit" value="<?php echo $max_blogs; ?>" size="20"></p>
				
			
				<p class="submit">
				<input type="submit" name="Submit" value="<?php _e('Update Options', 'blog_limit' ) ?>" />
				</p>
			</form>
		</div>
	
