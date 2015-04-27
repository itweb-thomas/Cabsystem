<tr class="listRow" data-customer_id="<?php echo $this->customer->customer_id; ?>">
	<td>
	<?php 
	echo !empty($this->customer->salutation_language_string) ? JText::_($this->customer->salutation_language_string) : '';
	echo !empty($this->customer->title_name) ? ' '.$this->customer->title_name : ''; 
	?>
    </td>
	<td><?php echo $this->customer->name; ?></td>
    <td>
	<?php 
	echo !empty($this->customer->street_name) ? $this->customer->street_name : '';
	echo !empty($this->customer->house) ? $this->customer->house : ''; 
	echo !empty($this->customer->stair) ? '/'.$this->customer->stair : ''; 
	echo !empty($this->customer->door) ? '/'.$this->customer->door : '';
	echo !empty($this->customer->district_zip) ? ', '.$this->customer->district_zip : '';
	echo !empty($this->customer->city_name) ? ' '.$this->customer->city_name : '';
	echo !empty($this->customer->district_name) ? ' '.$this->customer->district_name : '';
	?>
    </td>
	<td><?php echo $this->customer->phone; ?></td>
	<td><?php echo $this->customer->email; ?></td>
</tr>