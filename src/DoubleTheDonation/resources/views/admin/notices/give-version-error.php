<?php defined( 'ABSPATH' ) or exit; ?>
<strong><?php esc_html_e( 'Activation Error:', 'give-double-the-donation' ); ?></strong>
<?php esc_html_e( 'You must have', 'give-double-the-donation' ); ?> <a href="https://givewp.com" target="_blank">GiveWP</a> <?php esc_html_e( 'version', 'give-double-the-donation' );
?> <?php echo GIVE_VERSION; ?>+ <?php printf( esc_html__( 'for the %1$s add-on to activate', 'give-double-the-donation' ), GIVE_DTD_NAME ); ?>.

