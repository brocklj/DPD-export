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

		 
		//var_dump($shipping_methods);
	
	?>			
	
	<h1>CSV exports nastavení</h1>

	<form action="options.php" method="post">
		<?php settings_fields( 'dpd_settings_group' ); ?>
		<?php do_settings_sections( 'dpd_settings_group' ); ?>
		<?php $statuses = wc_get_order_statuses(); ?>
		<table class="form-table">
		<tbody>
		<tr valign="top">
		<th class="titledesc">
						<label>Status exportu</label>
						<td class="forminp">
		<select name="selec_state" onchange='this.form.submit()'>
			<?php foreach ($statuses as  $key => $value) {

				?> <option value="<?php echo $key;?>" <?php echo $key == get_option('selec_state')  ? "selected" : "";?>><?php echo $value;?></option>
				<?php
			}
			
			?>

		</select>
		</td>
		</th>
		</tr>
		</tbody>
		</table>
		<hr>
		<h1>Nastavení DPD</h1>
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

								foreach($shipping_methods as $method ) {
									
									?><option value="<?php echo $method->method_title; ?>"  <?php echo get_option('dpd_platba_predem') ==  $method->method_title  ? "selected" : "";?>><?php echo $method->method_title; ?></option>



									<?php
		//var_dump($method->method_title);
								}
								if(get_option('woocommerce_doruceni_doruceni_ppl_title')) {
								?>
								
								<option value="<?php echo $method->method_title; ?>"  <?php echo get_option('dpd_platba_predem') ==  get_option('woocommerce_doruceni_doruceni_ppl_title')  ? "selected" : "";?>><?php echo get_option('woocommerce_doruceni_doruceni_ppl_title'); ?></option>
								
								<?php } ?>
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
								foreach($shipping_methods as $method) {
									
									?><option value="<?php echo $method->method_title; ?>"  <?php echo get_option('dpd_dobirka') ==  $method->method_title  ? "selected" : "";?>><?php echo $method->method_title; ?></option>

									<?php
		//var_dump($method->method_title);
								}

								if(get_option('woocommerce_dobirka_dobirka_ppl_title')) {
								?>
								
								<option value="<?php echo $method->method_title; ?>"  <?php echo get_option('dpd_platba_predem') ==  get_option('woocommerce_dobirka_dobirka_ppl_title')  ? "selected" : "";?>><?php echo get_option('woocommerce_dobirka_dobirka_ppl_title'); ?></option>
								
								<?php } ?>
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
	register_setting('dpd_settings_group', 'selec_state');

}

?>