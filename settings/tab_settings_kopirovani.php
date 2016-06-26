<?php
/**
 * @package Woocommerce - DPD export settings
 * @version 0.1
 */
/*
*/
add_action('admin_init', 'register_dpd_settings');

function exports_tab_settings () {
	$shipping = new WC_Shipping();
	$shipping_methods = $shipping->load_shipping_methods();
		$ords = get_posts( array(
					'numberposts' => 50,
					'post_type'   => 'shop_order'
					) );

		$shipping_methods = ex_get_shipping_methods($ords);

		//var_dump($shipping_methods);
	
	?>			
	<h1>Nastavení DPD</h1>
	

	<form action="options.php" method="post">
		<?php settings_fields( 'dpd_settings_group' ); ?>
		<?php do_settings_sections( 'dpd_settings_group' ); ?>
		<h2>Platba předem</h2>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th class="titledesc">
						<label>DPD - platba předem</label>
						<td class="forminp">

							<select name="dpd_platba_predem">
								<option value="">Vyberte metodu</option>
								<?php

	//var_dump($shipping_methods);

								foreach($shipping_methods as $method=>$ids ) {
									
									?><option value="<?php echo $method; ?>"  <?php echo get_option('dpd_platba_predem') ==  $method  ? "selected" : "";?>><?php echo $method; ?></option>

									<?php
		//var_dump($method->method_title);
								}

								?>
							</select>
						</td>
					</th>
				</tr>
			</tbody>
		</table>
		
		<h2>Dobírka</h2>
		<table  class="form-table">
			<tbody>
				<tr valign="top">
					<th class="titledesc">
						<label>DPD - dobírka</label>
						<td class="forminp">
							<select name="dpd_dobirka">
								<option value="">Vyberte metodu</option>
								<?php
	//var_dump($shipping_methods);
								foreach($shipping_methods as $method=>$ids ) {
									
									?><option value="<?php echo $method; ?>"  <?php echo get_option('dpd_dobirka') ==  $method  ? "selected" : "";?>><?php echo $method; ?></option>

									<?php
		//var_dump($method->method_title);
								}

								?>
							</select>
						</td>
					</th>
				</tr>

				<tr valign="top">
					<th class="titledesc">
						<label>Typ platby</label>
						<td class="forminp">
							<select name="dpd_dobirka_platba">
								<option value="0"  <?php echo get_option('dpd_dobirka_platba') ==  "0" ? "selected" : "";?>>Pouze hotovost</option>
								<option value="2" <?php echo get_option('dpd_dobirka_platba') ==  "2"  ? "selected" : "";?>>Povolit platbu kartou</option>						
							</select>
						</td>
					</th>
				</tr>


			</tbody>
		</table>
		<?php

		submit_button();

		?>
	</form>


	<?php
}

function register_dpd_settings() {
	register_setting('dpd_settings_group', 'dpd_platba_predem');
	register_setting('dpd_settings_group', 'dpd_dobirka');
	register_setting('dpd_settings_group', 'dpd_dobirka_platba');

}

?>