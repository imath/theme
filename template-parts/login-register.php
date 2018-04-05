<?php
/**
 * Login's register form customizer template
 *
 * @package Thème
 *
 * @since 1.0.0
 */
?>

			<form name="registerform" id="registerform">
				<p>
					<label for="user_login"><?php esc_html_e( 'Identifiant', 'theme' ); ?><br />
						<input type="text" name="user_login" id="user_login" class="input" disabled="disabled" size="20" />
					</label>
				</p>
				<p>
					<label for="user_email"><?php esc_html_e( 'Adresse de messagerie', 'theme' ); ?><br />
						<input type="email" name="user_email" id="user_email" class="input" disabled="disabled" size="25" />
					</label>
				</p>

				<?php
				/**
				 * Fires following the 'Email' field in the user registration form.
				 *
				 * @since WordPress 2.1.0
				 */
				do_action( 'register_form' ); ?>

				<p id="reg_passmail">
					<?php esc_html_e( 'La confirmation d’inscription vous sera envoyée par e-mail.', 'theme' ); ?>
				</p>

				<br class="clear" />

				<p class="submit">
					<input type="submit" name="wp-submit" id="wp-submit" disabled="disabled" class="button button-primary button-large" value="<?php esc_attr_e( 'Inscription', 'theme' ); ?>" />
				</p>
			</form>
