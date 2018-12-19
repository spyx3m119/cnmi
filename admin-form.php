<form action='options.php' method='post'>
		<div class="cmni_settings_heading_container">
			<div class="cmni_settings_heading">
				<div class="cmni_settings_heading_img"><a href="http://conklinmedia.com" target="_blank"><img src="<?php echo( plugin_dir_url( __FILE__ ) . "assets/img/logo.png");?>"></a></div>
				<div class="cmni_settings_heading_tittle"><span class="cmni_settings_header ">Conklin Media - Navistar Integration Settings Page</span><span class="cmni_version">v1.1.1 - <a href="http://vladparole.ml" target="_blank">Blade</a> | <a href="https://bennn.me/" target="_blank">Ben</a></span></div>
			</div>
		</div>
		<?php
		settings_fields( 'CMNIPage' );
		do_settings_sections( 'CMNIPage' );
		submit_button();
		?>

	</form>
	<!-- <div class="cmni-footer"><a href="http://vladparole.ml" target="_blank">BladeParole</a></div> -->
	