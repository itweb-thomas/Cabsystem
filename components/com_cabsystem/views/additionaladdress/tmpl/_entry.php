<tr class="listRow" data-additionaladdress_id="<?php echo $this->additionaladdress->additionaladdress_id; ?>">
	<td><?php echo $this->additionaladdress->name; ?></td>
	<td><?php echo $this->additionaladdress->districts; ?></td>
    <?php
	foreach($this->additionaladdress->cartype_prices as $cartype_price)
	{
		if(!empty($cartype_price))
		{
			echo '<td>'.$cartype_price['price'].'</td>';
		}
	}
	?>
</tr>