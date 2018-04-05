<?php
/**
 * Multisite register form customizer template
 *
 * @package Thème\template-parts
 *
 * @since 1.0.0
 */
?>
			<div id="signup-content" class="widecolumn">
				<div class="mu_register wp-signup-container">
					<h2>
						<?php
						/* translators: %s: name of the network */
						printf( __( 'Obtenez votre propre compte %s en quelques secondes', 'theme' ), get_network()->site_name );
						?>
					</h2>
					<form id="setupform">
						<label for="user_name"><?php esc_html_e( 'Identifiant :', 'theme' ); ?></label>
						<input name="user_name" type="text" id="user_name" disabled="disabled" autocapitalize="none" autocorrect="off" maxlength="60" />
						<br />
						<?php esc_html_e( '(Doit contenir au moins 4 caractères, uniquement des lettres ou des chiffres.)', 'theme' ); ?>

						<label for="user_email"><?php esc_html_e( 'Adresse e-mail :', 'theme' ) ?></label>
						<input name="user_email" type="email" id="user_email" disabled="disabled" maxlength="200" />
						<br />
						<?php esc_html_e( 'Nous enverrons votre confirmation d’inscription à cette adresse. Vérifiez donc bien qu’elle est correcte avant de continuer.', 'theme' ); ?>

						<p>
						<?php if ( 'all' === theme_active_signup() ) : ?>
							<input id="signupblog" type="radio" name="signup_for" value="blog" disabled="disabled" />
							<label class="checkbox" for="signupblog"><?php esc_html_e( 'Donnez-moi un site !', 'theme' ); ?></label>
							<br />
							<input id="signupuser" type="radio" name="signup_for" value="user" disabled="disabled" />
							<label class="checkbox" for="signupuser"><?php esc_html_e( 'Juste l’identifiant, s’il vous plaît.', 'theme' ); ?></label>
						<?php endif; ?>
						</p>

						<p class="submit">
							<input type="submit" name="submit" class="submit" value="<?php esc_attr_e( 'Suivant', 'theme') ?>" disabled="disabled" />
						</p>
					</form>
				</div>
			</div>
