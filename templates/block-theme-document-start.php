<?php
/**
 * Minimal document start for block themes (no header.php).
 * Ensures wp_head() and body open so styles/scripts load.
 *
 * @package Job_Connect
 */

defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php
wp_body_open();
if ( function_exists( 'block_header_area' ) ) {
	echo '<header class="wp-block-template-part">';
	block_header_area();
	echo '</header>';
}
?>
