<?php defined( 'ABSPATH' ) or exit; ?>
<strong><?php _e( 'Activation Error:', 'give-double-the-donation' ); ?></strong>
<?php _e( 'You must have', 'give-double-the-donation' ); ?> <a href="https://givewp.com" target="_blank">GiveWP</a> <?php _e( 'version', 'give-double-the-donation' ); ?> <?php
echo GIVE_VERSION; ?>+ <?php printf( esc_html__( 'for the %1$s add-on to activate', 'give-double-the-donation' ), GIVE_DTD_NAME ); ?>.

