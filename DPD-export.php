<?php
/**
 * @package Woocommerce - DPD exportlokpkp
 * @version 0.1
 */
/*
Plugin Name: DPD export
Plugin URI: /.
Description: Plugin provedrní exportu CSV pro dopravce.
Version: 0.1
Author: Bröckl.net
Author URI: http://brockl.net
*/
require_once("tab_settings.php");
add_action( 'admin_menu', 'my_admin_menu' );

function my_admin_menu() {
	
	add_submenu_page( 'woocommerce', 'Exports - delivery Settings', 'DPD Exports', 'manage_options', 'DPDExports', 'Exports' ); 
}

    //add_submenu_page( 'woocommerce', 'Exports', 'Exports', 'manage_options', 'Exports', 'my_custom_submenu_page_callback' ); 


function dpd_create_csv_data($ids) {
	$pole = array();
	foreach ($ids as $id=>$packages) {
		$order = wc_get_order($id);

		$address = $order->get_shipping_address();
		$total = $order->get_total();
		
		#Typ příjemce 	Jméno příjemce 	x	DIČ příjemce	Id země příjemce 	Ulice příjemce 	Č.p. příjemce 	Město příjemce 	PSČ příjemce 	Tel. č. příjemce 	Ref. č. zásilky 1 	Ref. č. zásilky 2 	Ref. č. zásilky 3 	Ref. č. zásilky 4 	Datum svozu 	Produkt	Tel. pro Parcel Shop	Predict email	Částka COD 	Měna COD 	Typ platby 	COD variabilní symbol 	Kompletní doručení 	Připojištění částka 	Připojíštění měna 	Výměnná zásilka 	Parcel shop id 	Parcel shop společnost 	Parcel Shop ulice 	Parcel shop č.p.	PS PSČ 	Parcel shop město 	PS země 	Parcel shop tel.č. 	Id check id 	Id check název 	Referenční číslo balíku 1 	Referenční číslo balíku 2 	Referenční číslo balíku 3 	Referenční číslo balíku 4 	Hmotnost 	Délka 	Šířka 	Výška 	Objem 	Autentication id 	Rozdělení částky dobírky 	Hash code 	Počet balíků v zásilce 	Tel. pro DPD Private
		$pole[] = array('',								 #Typ příjemce
						$order->shipping_first_name." ".$order->shipping_last_name, #Jméno příjemce
						'', 							#DIČ příjemce
						$order->shipping_country, 		#Id země příjemce
						$order->shipping_address_1, 	#Ulice příjemce
						$order->shipping_address_2,		#Č.p. příjemce 
						$order->shipping_city,			#Město příjemce
						$order->shipping_postcode,		#PSČ příjemce
						$order->billing_phone,			#Tel. č. příjemce
						$packages >= 1 ? $order->id.'001':'', 				#Ref. č. zásilky 1
						$packages >= 2 ? $order->id.$order->id.'002':'',				#Ref. č. zásilky 2 
						$packages >= 3 ? $order->id.$order->id.'003':'',				#Ref. č. zásilky 3
						$packages >= 4 ? $order->id.$order->id.'004':'',				#Ref. č. zásilky 4
						'',								#datum svozu
						'',								#Produkt	Tel. pro Parcel Shop
						$order->billing_email,			#Predict email
							$order->get_shipping_method() ==
						get_option('dpd_dobirka') ?
						 $total : '',					#Částka COD
							$order->get_shipping_method() ==
						get_option('dpd_dobirka') ?
						 $order->get_order_currency() : '',			#Mena COD
						$order->get_shipping_method() ==
						get_option('dpd_dobirka') ?
						 get_option('dpd_dobirka_platba') : '',	#Typ platby
						$order->id,						#COD variabilní symbol
						'',								#Kompletní doručení
						$total,							#Připojištění částka
						$order->get_order_currency(),	#Připojíštění měna
						'',								#Výměnná zásilka
						'',								#Parcel shop id
						'',								#Parcel shop společnost
						'',								#Parcel Shop ulice
						'',								#Parcel shop č.p
						'',								#PS PSČ
						'',								#Parcel shop město
						'',								#PS země
						'',								#Parcel shop tel.č.
						'',								#Id check id
						'',								#Id check název
						'',								#Referenční číslo balíku 1
						'',								#Referenční číslo balíku 2	
						'',								#Referenční číslo balíku 3
						'',								#Referenční číslo balíku 4
						'',								#Hmotnost
						'',								#Délka
						'',								#sirka
						'',								#vyska
						'',								#objem
						'',								#Autentication id
						$order->get_shipping_method() ==
						get_option('dpd_dobirka') ?
						 round($total/$packages) : '',								#Rozdělení částky dobírky
						'',								#Hash code
						$packages,								#Počet balíků v zásilce
						'');							#Tel. pro DPD Private
		//var_dump(wc_get_order($id)->get_shipping_method());
	}
	return $pole;
}

function ex_get_shipping_methods($orders) {
	$array = array();
	foreach ($orders as $key => $value) {
		$id = $value->ID;		
		$method = wc_get_order($id)->get_shipping_method();

		$array[$method][] = $id;
		

		
	}
	return $array;

}

function select_status() {
	$statuses = wc_get_order_statuses();
	$_POST['selec_state'] ? $selected = $_POST['selec_state'] : $methods_sel = array();


	?>

	<form action="" method="post">
		Vyber: 
		<select name="selec_state" onchange='this.form.submit()'>
			<?php foreach ($statuses as  $key => $value) {

				?> <option value="<?php echo $key;?>" <?php echo $selected == $key  ? "selected" : "";?>><?php echo $value;?></option>
				<?php
			}
			
			?>

		</select>

	</form>

	<?php

}



function Exports() {
	$tab = isset($_GET['tab']) ? $_GET['tab'] : "general";
	?>
	<div class="wrap">
		<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
			<a href="<?php echo $_SERVER['REQUEST_URI']; ?>&amp;tab=general" class="nav-tab <?php echo $tab == "general"? "nav-tab-active":"";  ?>">DPD</a>
			<a href="<?php echo $_SERVER['REQUEST_URI']; ?>&amp;tab=Settings" class="nav-tab <?php echo $tab == "Settings"? "nav-tab-active":"";  ?>">Nastavení</a>		</h2>
			<?php
			
			switch ($tab) {
				case "general":
				

				?>

			</div>

			<div>
				<h2>CSV Exports - DPD</h2>
				
			</div>
			<?php

			//select_status();


			$methods_sel = array();
			$_POST['method'] ? $methods_sel = $_POST['method'] : $methods_sel = array();

			if ( get_option('selec_state')) {

				$orders = get_posts( array(
					'numberposts' => -1,
					'post_type'   => 'shop_order',
					'post_status' => get_option('selec_state')
					) );

				if (get_option('dpd_dobirka') == '' or get_option('dpd_platba_predem')== '' or get_option('dpd_dobirka') == get_option('dpd_platba_predem')) {
					echo "<b>Chyba:</b> Metody chybně nastaveny.";
					exit;
				}



				$methods_orders = ex_get_shipping_methods($orders);
				$methods_sel = array();

				if (array_key_exists(get_option('dpd_dobirka'), $methods_orders)) {
					$methods_sel[get_option('dpd_dobirka')] = $methods_orders[get_option('dpd_dobirka')];
				}
				if (array_key_exists(get_option('dpd_platba_predem'), $methods_orders)) {
					$methods_sel[get_option('dpd_platba_predem')] = $methods_orders[get_option('dpd_platba_predem')];
				}

				// var_dump($methods_sel);


				if (!isset($_POST["packages"])) {

					?> 

					<form method="post" action="">
						<input type="hidden" name="selec_state" value="<?php echo $selected; ?>">


						<?php

					foreach ($methods_sel as $id_arr) {
							foreach ($id_arr as $key => $id) {
								$order = wc_get_order($id);

								$add = preg_replace('#<[^>]+>#', ', ', $order->get_shipping_address());
								$items = $order->get_items();

								//var_dump($items);

								


								echo '#'.$id.' '.$add.'<br>';
								?><ul style="padding-left:10px;"><?php
								$i = 1;
								foreach ($items as $key => $item) {
									echo '<li>'.$i.' '.$item["name"].' '.$item['item_meta']["_qty"][0].'ks</li>';
									++$i;

								}

								?></ul><?php
								echo '<input name="packages['.$id.']" type="number" min="1" max="4" value="1"> balíků<hr>';


							}
						}
						submit_button()	;
						?></form> <?php
					}







					if ($methods_sel = $_POST["packages"]) {
						$selected_orders = array();

						//var_dump($methods_sel);

						foreach ($methods_sel as $id => $packages) {

								$selected_orders[$id] =  $packages;
							


						}



						$file_name = 'export_dpd.csv';
						$connect = array('url'=>plugin_dir_url( __FILE__ ).$file_name, 'path'=>plugin_dir_path( __FILE__ ).$file_name);



			//var_dump($selected_orders);



						$handle = fopen($connect['path'], 'w');


						$list = dpd_create_csv_data($selected_orders);

			//var_dump($list);

							?><div style="background: white;padding: 5px;" class="wrap"><h2>Náhled CSV</h2><?php
						foreach ($list as $row) {
		//$row = array_map("utf8_decode", $row);
				//echo $selected_orders[$i];
							foreach ($row as $one) {
								echo $one.", ";

							} echo'<br>';
				fputcsv($handle, $row); // fgetcsv($file,null,';');
				
			}
			fclose($handle);

		
		?>
	

<form action="" method="post">
		<input type="hidden" name="selec_state" value="<?php echo $selected; ?>">
		<br>
		 <input type="submit" value="Edit" class="button button-primary">

		</form>	</div>
		<form action="<?php echo $connect['url'];?>"><?php
		submit_button("Export");
		?></form>
		

		<?php
	}


	}	break;
	case "Settings":
	exports_tab_settings();
	break;
	default:


}

}


?>