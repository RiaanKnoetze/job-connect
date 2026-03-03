<?php
/**
 * Minimal document end for block themes (no footer.php).
 *
 * @package Job_Connect
 */

defined( 'ABSPATH' ) || exit;

if ( function_exists( 'block_footer_area' ) ) {
	echo '<footer class="wp-block-template-part">';
	block_footer_area();
	echo '</footer>';
}
wp_footer();
?>
</body>
</html>
