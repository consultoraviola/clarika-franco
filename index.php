<?php
get_header();
the_post();
$post_id = get_the_ID();
?>
<main id="main-element">
	<h1><?php the_title(); ?></h1>
</main>
<?php get_footer(); ?>