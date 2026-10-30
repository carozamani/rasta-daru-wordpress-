<?php
/**
 * Product quantity inputs — always the shared .sp-qty component (PDP style).
 *
 * @see     https://woo.com/document/template-structure/
 * @package StorePlus Child
 * @version 9.4.0
 *
 * @var bool   $readonly If the input should be set to readonly mode.
 * @var string $type     The input type attribute.
 */

defined( 'ABSPATH' ) || exit;

/* translators: %s: Quantity. */
$label = ! empty( $args['product_name'] )
	? sprintf( esc_html__( '%s quantity', 'woocommerce' ), wp_strip_all_tags( $args['product_name'] ) )
	: esc_html__( 'Quantity', 'woocommerce' );

$qty_val = isset( $input_value ) ? max( 1, (int) $input_value ) : 1;
$size    = is_cart() ? 'sm' : 'md';

?>
<div class="quantity irantheme-single-product-quantity sp-qty-wrap" data-sp-qty-wrap="1">
	<?php do_action( 'woocommerce_before_quantity_input_field' ); ?>

	<label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>"><?php echo esc_attr( $label ); ?></label>

	<?php
	if ( function_exists( 'storeplus_child_qty_stepper' ) ) {
		echo storeplus_child_qty_stepper( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			array(
				'qty'  => $qty_val,
				'size' => $size,
			)
		);
	}
	?>

	<input
		type="<?php echo esc_attr( $type ); ?>"
		<?php echo $readonly ? 'readonly="readonly"' : ''; ?>
		id="<?php echo esc_attr( $input_id ); ?>"
		class="qty irantheme-input-text-quantity sp-qty-input <?php echo esc_attr( join( ' ', (array) $classes ) ); ?>"
		name="<?php echo esc_attr( $input_name ); ?>"
		value="<?php echo esc_attr( $input_value ); ?>"
		aria-label="<?php esc_attr_e( 'Product quantity', 'woocommerce' ); ?>"
		size="4"
		min="<?php echo esc_attr( $min_value ); ?>"
		max="<?php echo esc_attr( 0 < $max_value ? $max_value : '' ); ?>"
		<?php if ( ! $readonly ) : ?>
		step="<?php echo esc_attr( $step ); ?>"
		placeholder="<?php echo esc_attr( $placeholder ); ?>"
		inputmode="<?php echo esc_attr( $inputmode ); ?>"
		autocomplete="<?php echo esc_attr( isset( $autocomplete ) ? $autocomplete : 'on' ); ?>"
		<?php endif; ?>
	/>

	<?php do_action( 'woocommerce_after_quantity_input_field' ); ?>
</div>
<?php
