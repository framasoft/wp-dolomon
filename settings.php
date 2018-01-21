<?php
// vim:set ft=php noexpandtab:
?>
<style>
	#dolomon-form input {
		width: 100%
	}
</style>
<div class="wrap">
	<h1><?php _e( 'Dolomon settings', 'dolomon' ) ?></h1>
	<div id="dolomon-alert">
		<?php if ( isset( $notice_message ) ) : ?>
			<div class="notice is-dismissible notice-<?php echo $settings_valid ? 'success' : 'error'; ?>">
				<p><strong><?php echo $notice_message; ?></strong></p>
			</div>
		<?php endif; ?>
	</div>
	<form method="post" action="<?php echo $action_url ?>" id="dolomon-form">
		<?php wp_nonce_field( 'dolomon-settings' ); ?>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						<label for="dolomon-url"><?php _e( 'Dolomon server URL', 'dolomon' ) ?></label>
					</th>
					<td>
						<input id="dolomon-url" name="dolomon-url" type="url" placeholder="https://dolomon.example.org" required value="<?php echo $url; ?>">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="dolomon-app_id"><?php _e( 'Application id', 'dolomon' ) ?></label>
					</th>
					<td>
						<input id="dolomon-app_id" name="dolomon-app_id" type="text" required value="<?php echo $appid; ?>">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="dolomon-app_secret"><?php _e( 'Application secret', 'dolomon' ) ?></label>
					</th>
					<td>
						<input id="dolomon-app_secret" name="dolomon-app_secret" type="text" required value="<?php echo $appsecret; ?>">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="dolomon-cache_expiration"><?php _e( 'Cache expiration delay (in minutes)', 'dolomon' ) ?></label>
					</th>
					<td>
						<input id="dolomon-cache_expiration" name="dolomon-cache_expiration" type="text" required value="<?php echo $cache_expiration; ?>">
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<button id="dolomon-save" class="button-primary"><?php _e( 'Save Changes' ) ?></button>
		</p>
	</form>
</div>
