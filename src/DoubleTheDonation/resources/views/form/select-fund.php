<?php defined( 'ABSPATH' ) or exit; ?>
<div class="form-row form-row-wide">
	<div class="give-select-fund-row">
		<label for="give-funds-select">
			<?php echo $label; ?>
		</label>
		<select id="give-funds-select" class="give-select" name="give-selected-fund">
			<?php foreach ( $funds as $fund ) : ?>
				<option value="<?php echo $fund->getId(); ?>">
					<?php echo $fund->getTitle(); ?>
					<?php if ( ! empty( $fund->getDescription() ) ) : ?>
						- <?php echo $fund->getDescription(); ?>
					<?php endif; ?>
				</option>
			<?php endforeach; ?>
		</select>
	</div>
</div>
