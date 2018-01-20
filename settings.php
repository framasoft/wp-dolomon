<?php
// vim:set sw=4 ts=4 sts=4 ft=php expandtab:
?>
<style>
	#dolomon-form input {
		width: 100%
	}
</style>
<div class="wrap">
	<h1><?php _e( 'Dolomon settings', 'dolomon' ) ?></h1>
	<div id="dolomon-alert">
		<?php
		// Display message if any
		if ( isset( $msg ) ) {
			?>
			<div class="updated settings-error notice is-dismissible">
				<p>
					<strong>
					<span style="display: block; margin: 0.5em 0.5em 0 0; clear: both;">
						<?php echo $msg; ?>
					</span>
					</strong>
				</p>
				<button type="button" class="notice-dismiss">
					<span class="screen-reader-text"><?php _e( 'Dismiss this notice.' ) ?></span>
				</button>
			</div>
			<?php
		}
		?>
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
