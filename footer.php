<?php
needs_script('v-newsletter');

$logo = get_field('logo_footer_desktop', 'options');
$redes = get_field('redes', 'options');

$text_newsletter = get_field('texto_newsletter_en', 'options');
$imagen_de_fondo = get_field('imagen_newsletter', 'options');
$titulo_newsletter = get_field('titulo_newsletter_en', 'options');

$direccion = get_field('direccion', 'options');
$logo_capital = get_field('logo_capital', 'options');

// Ver si la página en la que estoy es /formulario-de-contacto/
$pagina_contacto = get_page_by_path('formulario-de-contacto');

// Verificar si la página fue encontrada
if ($pagina_contacto) {
	$pagina_contacto_slug = $pagina_contacto->post_name;

	if (is_page($pagina_contacto_slug)) {
		$contacto = true;
	} else {
		$contacto = false;
	}
} 
?>

<footer class="Footer" id="footer">
	<section class="Footer__main no-overflow">
		<div class="container">
			<div class="row">
				<div class="gr-4 gr-12@medium">
					<div class="row">
						<div class="Footer__main__logo">
							<a title="Ir a página de inicio" href="<?php echo home_url() ?>">
								<img src="<?php echo $logo['url']; ?>">
							</a>
						</div>
						<ul class="Footer__main__datos">
							<?php
							$telefono = get_field('telefono', 'options');
							$telefono_sin_espacios = str_replace(' ', '', $telefono);
							$mail = get_field('mail', 'options');
							?>
							<li class="Footer__main__datos--phone">
								<a href="tel:<?php echo $telefono_sin_espacios; ?>"><?php echo $telefono; ?></a>
							</li>
							<li class="Footer__main__datos--message">
								<a title="Mandar un mensaje al email" href="mailto:<?php echo $mail; ?>"><?php echo $mail; ?></a>
							</li>
							<li class="Footer__main__datos--direction">
								<p>
									<?php echo $direccion; ?>
								</p>
							</li>
						</ul>
					</div>
				</div>
				<div class="gr-7 gr-12@medium">
					<?php
					wp_nav_menu(
						array(
							'menu'              => "menu-footer",
							'theme_location'    => "menu-footer",
							'menu_class'        => "Footer__main__navegacion",
							'menu_id'           => "footer-menu",
						)
					);
					?>
				</div>
				<div class="gr-1 gr-12@medium">
					<div class="Footer__main__rrss--ul">
						<a class="instagram" title="Instagram" target="_blank" href="<?php echo $redes[0]['instagram']; ?>"></a>
						<a class="facebook" title="Facebook" target="_blank" href="<?php echo $redes[0]['facebook']; ?>"></a>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="Footer__copy">
		<div class="container">
			<div class="gr-12 gr-centered">
				<p class="Footer__copy--text"><a href="<?php echo home_url() . '/privacy-policy/'; ?>">© <?php echo date('Y'); ?> Terms/conditions and policies.</a> <span class="hide@medium">| Development: <a href="https://tuentornovirtual.com/" target="_blank">Tu Entorno Virtual</a></p></span>
			</div>
		</div>
	</section>
</footer>
<?php
wp_footer();
?>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
</body>

</html>