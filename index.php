<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Condensed:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Arbutus+Slab&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=IBM+Plex+Mono&display=swap" rel="stylesheet">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
		<div id="page" class="site">
			<header id="masthead" class="site-header">
				<div class="site-branding">
					<?php the_custom_logo(); ?>
						<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
						<p class="site-description">
							<?php bloginfo( 'description' ); ?>
						</p>
				</div>
			  </header>

				<div id="content" class="site-content">
					<?php
					$post   = get_post( ( get_theme_mod( 'zen_page_setting' ) ) );
					$output = apply_filters( 'the_content', $post->post_content );
					echo $output;
					?>
				</div>

		</div>

		<?php wp_footer(); ?>
</body>

</html>
