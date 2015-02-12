<tr class="listRow" data-order_id="<?php echo $this->order->order_id; ?>" data-driver_id="<?php echo $this->order->driver_id; ?>">
    <td width="70%">
	<?php 
		echo "<strong>Bestellung Nr. ".$this->order->order_id.'</strong>'.' | '; 
		echo date("d.m.Y H:i", strtotime($this->order->datetime));
	?>
    <?php 
        echo '<br/>Von '.JText::_($this->order->from_ordertype_language_string);
        if ($this->order->from_ordertype_type != 'airport') {
			 echo ' (';
			echo $this->order->from_street_name.' ';
			echo !empty($this->order->from_house) ? $this->order->from_house : ''; 
			echo !empty($this->order->from_stair) ? '/'.$this->order->from_stair : ''; 
			echo !empty($this->order->from_door) ? '/'.$this->order->from_door : '';
			echo ', ';
			echo $this->order->from_district_zip.' '.$this->order->from_city_name.' '.$this->order->from_district_name;
			echo ')';
		}
		elseif ($this->order->from_ordertype_type == 'airport') {
			echo ' (';
			echo $this->order->flight_number.' '.$this->order->destionation_city_name.' '.$this->order->flight_time;
			echo ')';
		}
    ?>
    <?php 
        echo '<br/>Nach '.JText::_($this->order->to_ordertype_language_string);		
		if ($this->order->to_ordertype_type != 'airport') {
			echo ' (';
			echo $this->order->to_street_name.' ';
			echo !empty($this->order->to_house) ? $this->order->to_house : ''; 
			echo !empty($this->order->to_stair) ? '/'.$this->order->to_stair : ''; 
			echo !empty($this->order->to_door) ? '/'.$this->order->to_door : '';
			echo ', ';
			echo $this->order->to_district_zip.' '.$this->order->to_city_name.' '.$this->order->to_district_name;
			echo ')';
		}
		/*elseif ($this->order->to_ordertype_type == 'airport') {
			echo ' (';
			echo $this->order->flightnumber.' '.$this->order->destionation_city_name.':'.$this->order->time;
			echo ')';
		}*/
    ?>
    <?php
		echo ' (';
		echo !empty($this->order->additionaladdresses_name) ? ' '.mb_substr($this->order->additionaladdresses_name,0,1) : 'keine Zusatzadressen';
		echo ')';
	?>
    <?php 
    echo '<br/>Kunde';
    echo !empty($this->order->salutation_name) ? $this->order->salutation_name : ''; 
    echo !empty($this->order->title_name) ? ' '.$this->order->title_name : ''; 
    echo ' '.$this->order->name;
    echo !empty($this->order->phone) ? ' | '.$this->order->phone : ''; 
    echo !empty($this->order->email) ? ' | '.$this->order->email : ''; 
    ?>
    </td>
    <td width="20%" align="left">
    <?php 
    echo $this->order->cartype_name;
    echo !empty($this->order->luggage) ? '<br/>'.$this->order->luggage.' Koffer' : '<br/>0 Koffer'; 
    echo !empty($this->order->handluggage) ? '<br/>'.$this->order->handluggage.' Handgep.' : '<br/>0 Handgep.'; 
    echo !empty($this->order->driver_name) ? '<br/>'.$this->order->driver_name.'' : '<br/>kein Fahrer'; 
    ?>
    </td>
    <td width="10%" align="right">
    <?php 
    echo '€ '.$this->order->price;
    echo !empty($this->order->paymentmethod_name) ? '<br/>'.$this->order->paymentmethod_name : ''; 
    ?>
    </td>
</tr>