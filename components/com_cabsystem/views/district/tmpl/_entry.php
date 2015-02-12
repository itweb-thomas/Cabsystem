<tr class="listRow" data-district_id="<?php echo $this->district->district_id; ?>">
	<td><?php echo $this->district->zip; ?></td>
	<td><?php echo $this->district->district; ?></td>
	<td><?php echo $this->district->city_name; ?></td>
    <?php
	foreach($this->district->cartype_prices as $cartype_price)
	{
		if(!empty($cartype_price))
		{
			echo '<td>';
			if($cartype_price['price'] >= 0) {
				echo $cartype_price['price'];
			}
			else {
				echo "Anfrage";
			}
			echo '</td>';
		}
	}
	foreach($this->district->cartype_prices_additional_address as $cartype_price_additional_address)
	{
		if(!empty($cartype_price_additional_address))
		{
			echo '<td>';
			if($cartype_price_additional_address['additional_address_price'] >= 0) {
				echo $cartype_price_additional_address['additional_address_price'];
			}
			else {
				echo "Anfrage";
			}
			echo '</td>';
		}
	}
	?>
</tr>