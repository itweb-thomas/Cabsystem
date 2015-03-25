<tr class="listRow" id="list-row-<?php echo $this->order->order_id; ?>" data-order_id="<?php echo $this->order->order_id; ?>" data-driver_id="<?php echo $this->order->driver_id; ?>">
	<td><?php echo $this->order->order_id; ?></td>
	<td><?php echo JText::_('COM_CABSYSTEM_ORDER_STATUS'.$this->order->status);?></td>
	<td class="text-center">
	<?php 
	switch($this->order->status) 
	{
		//neu
		case 0:
			$status = '<i class="fa fa-taxi fa-2x"></i>';
		break;
		//wartet
		case 1:
			$status = '<i class="icon-pending fa fa-clock-o fa-2x"></i>';
		break;
		//akzeptiert
		case 2:
			$status = '<i class="icon-active fa fa-check-circle fa-2x"></i>';
		break;
		//storniert
		case 3:
			$status = '<i class="icon-inactive fa fa-minus-circle fa-2x"></i>';
		break;
		default:
			$status_textclass = '';
			$status = '<i class="fa fa-question fa-2x"></i>';
	}
    echo $status;
	echo '<br/>';
	echo !empty($this->order->creator) ? '<span data-toggle="tooltip" data-placement="bottom" title="'.$this->creator_object->name.'" class="show-tooltip-hover fa fa-user fa-2x"></span>' : '<span data-toggle="tooltip" data-placement="bottom" title="Kunde über Website" class="show-tooltip-hover fa fa-globe fa-2x"></span>';
	echo '<br/>';
	echo !empty($this->order->postorder_id) ? '<span data-toggle="tooltip" data-placement="bottom" title="Hat Rückfahrt (Nr. '.$this->order->postorder_id.')" class="show-tooltip-hover fa fa-chain fa-2x"></span>' : '';
	?>
    </td>
    <td>
	<?php 
	echo '<span class="cartype'.$this->order->cartype_id.'"><strong>'.$this->order->cartype_name.'</strong></span>';
	echo !empty($this->order->persons) ? '<br/><strong>'.$this->order->persons.' Personen</strong>' : '<br/><strong>0 Personen</strong>'; 
	echo !empty($this->order->luggage) ? '<br/><strong>'.$this->order->luggage.' Koffer</strong>' : '<br/><strong>0 Koffer</strong>'; 
	echo !empty($this->order->handluggage) ? '<br/><strong>'.$this->order->handluggage.' Handgep.</strong>' : '<br/><strong>0 Handgep.</strong>'; 
	echo !empty($this->order->child_seat) ? '<br/><strong>'.$this->order->child_seat.' Kindersitze</strong>' : '<br/><strong>0 Kindersitze</strong>'; 
	echo !empty($this->order->maxi_cosi) ? '<br/><strong>'.$this->order->maxi_cosi.' Maxi Cosi</strong>' : '<br/><strong>0 Maxi Cosi</strong>'; 
	echo !empty($this->order->child_seat_elevation) ? '<br/><strong>'.$this->order->child_seat_elevation.' K.sitzerhöh.</strong>' : '<br/><strong>0 K.sitzerhöh.</strong>'; 
	$driverModel = new CabsystemModelsDriver();
	$driverModel->set('_cartype_id',$this->order->cartype_id);
	$drivers = $driverModel->listItems();
	?>
    <br/>
    <div class="btn-group">
		<button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
    <?php echo (!empty($this->order->driver_name)) ? mb_substr($this->order->driver_name,0,11) : 'Kein Fahrer';?> <span class="caret"></span></button>
      <ul class="dropdown-menu" role="menu">
		<?php
			if(empty($this->order->driver_id)) {
				echo '<li><a class="setDriver" data-orderid="'.$this->order->order_id.'" data-driverid=""><strong>Kein Fahrer</strong></a></li>';
			}
			else {
				echo '<li><a class="setDriver" data-orderid="'.$this->order->order_id.'" data-driverid="">Kein Fahrer</a></li>';
			}
			foreach($drivers as $driver) {
				if($driver->driver_id == $this->order->driver_id) {
					echo '<li><a class="setDriver" data-orderid="'.$this->order->order_id.'" data-driverid="'.$driver->driver_id.'"><strong>'.$driver->name.'</strong></a></li>';	
				}
				else {
					echo '<li><a class="setDriver" data-orderid="'.$this->order->order_id.'" data-driverid="'.$driver->driver_id.'">'.$driver->name.'</a></li>';	
				}
			}
		?>
      </ul>
    </div>
	<!--<select class="form-control select2-in-table" id="datatable-setdriver-select-<?php echo $this->order->order_id;?>">
	  <?php
		echo '<option value="">Kein Fahrer</option>';
		foreach($drivers as $driver) {
			$selected = "";
			if($this->order->driver_id == $driver->driver_id)
			{
				$selected = 'selected="selected"';
			}
			
			echo '<option value="'.$driver->driver_id.'" '.$selected.'>'.$driver->name.'</option>';	
		}
	  ?>
	</select>
    <br />
    <button type="button" class="btn btn-sm btn-default btn-block setDriver" data-type="table" data-selectid="<?php echo $this->order->order_id;?>"><li class="fa fa-envelope-o"></li> Zuweisen</button>-->
    </td>
    <td><?php echo strtotime($this->order->datetime) ?></td>
	<td>
			<?php
				echo '<strong>'.date("d.m.Y", strtotime($this->order->datetime)).'</strong><br/>'.date("H:i", strtotime($this->order->datetime));
				echo '<br/><br/><em>Erstellt am</em><br/>';
				echo '<strong>'.date("d.m.Y", strtotime($this->order->created)).'</strong><br/>'.date("H:i", strtotime($this->order->created));
				if($this->order->modified != null) {
					echo '<br/><em>Zuletzt bearbeitet</em><br/>';
					echo '<strong>'.date("d.m.Y", strtotime($this->order->modified)).'</strong><br/>'.date("H:i", strtotime($this->order->modified));
				}
			?>
	</td>
	<td>
	<?php 
	//Wenn abgelaufen
	if(mktime() > strtotime($this->order->datetime)) {
		echo "abgelaufen";	
	}	
	else {
		echo "zukünftig";
	}
	?>
    </td>
	<td>
	<?php 
		$icon = '';
		switch($this->order->from_ordertype_type) {
			case 'airport':
				$icon = '<li class="fa fa-plane fa-2x"></li>';
			break;
			case 'address':
				$icon = '<li class="fa fa-home fa-2x"></li>';
			break;
			case 'railway':
				$icon = '<li class="fa fa-suitcase fa-2x"></li>';
			break;
			case 'hotel':
				$icon = '<li class="fa fa-building fa-2x"></li>';
			break;
			case 'office':
				$icon = '<li class="fa fa-building-o fa-2x"></li>';
			break;
		}
		echo '<strong>'.$icon.' '.JText::_($this->order->from_ordertype_language_string).'</strong>';
		if ($this->order->from_ordertype_type != 'airport') {
			echo '<br/>';
			echo $this->order->from_street_name.' ';
			echo !empty($this->order->from_house) ? $this->order->from_house : ''; 
			echo !empty($this->order->from_stair) ? '/'.$this->order->from_stair : ''; 
			echo !empty($this->order->from_door) ? '/'.$this->order->from_door : '';
			echo ', ';
			echo $this->order->from_district_zip.' '.$this->order->from_city_name.'<br/>'.$this->order->from_district_name;
		}
		elseif ($this->order->from_ordertype_type == 'airport') {
			echo '<br/>';
			echo $this->order->flight_number.' '.$this->order->destionation_city_name.' '.date("H:i", strtotime($this->order->flight_time));
		}
	?>
    </td>
	<td>
	<?php 
		$icon = '';
		switch($this->order->to_ordertype_type) {
			case 'airport':
				$icon = '<li class="fa fa-plane fa-2x"></li>';
			break;
			case 'address':
				$icon = '<li class="fa fa-home fa-2x"></li>';
			break;
			case 'railway':
				$icon = '<li class="fa fa-suitcase fa-2x"></li>';
			break;
			case 'hotel':
				$icon = '<li class="fa fa-building fa-2x"></li>';
			break;
			case 'office':
				$icon = '<li class="fa fa-building-o fa-2x"></li>';
			break;
		}
		echo '<strong>'.$icon.' '.JText::_($this->order->to_ordertype_language_string).'</strong>';
		if ($this->order->to_ordertype_type != 'airport') {
			echo '<br/>';
			echo $this->order->to_street_name.' ';
			echo !empty($this->order->to_house) ? $this->order->to_house : ''; 
			echo !empty($this->order->to_stair) ? '/'.$this->order->to_stair : ''; 
			echo !empty($this->order->to_door) ? '/'.$this->order->to_door : '';
			echo ', ';
			echo $this->order->to_district_zip.' '.$this->order->to_city_name.'<br/>'.$this->order->to_district_name;
		}
		/*elseif ($this->order->to_ordertype_type == 'airport') {
			echo '<br/>';
			echo $this->order->flightnumber.' '.$this->order->destionation_city_name.':'.$this->order->time;
		}*/
	?>
    </td>
    <td>
	<?php 
	$additional_addresses = '';
	if(!empty($this->order->additional_address_districts)) {
		$additional_addresses = '<button type="button" class="btn btn-default btn-in-datatable" data-toggle="modal" data-target="#additionaladdressesModal'.$this->order->order_id.'">';
		foreach(json_decode($this->order->additional_address_districts) as $additional_address_district) {
			if(is_array($additional_address_district)) {
				$district = JTable::getInstance('districts','Table');
				$district->load($additional_address_district[0]);
				$additional_addresses .= $district->zip.'<br/>';
			}
			//Fuer die alten Restbestaende bei denen noch keine Adressen zu den Bezirken gespeichert wurden
			else {
				$district = JTable::getInstance('districts','Table');
				$district->load($additional_address_district);
				$additional_addresses .= $district->zip.'<br/>';
			}
		}
		$additional_addresses .= '</button>';
		$additional_addresses .= '<div class="modal fade" id="additionaladdressesModal'.$this->order->order_id.'" tabindex="-1" role="dialog" aria-labelledby="#additionaladdressesModalLabel'.$this->order->order_id.'" aria-hidden="true">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
		<h4 class="modal-title" id="commentModalLabel'.$this->order->order_id.'">Zusatzadressen</h4>
	  </div>
	  <div class="modal-body">';
	  
		if(!empty($this->order->additional_address_districts)) {
			foreach(json_decode($this->order->additional_address_districts) as $additional_address_district) {
				if(is_array($additional_address_district)) {
					$district = JTable::getInstance('districts','Table');
					$district->load($additional_address_district[0]);
					$additional_addresses .= $district->zip.' '.$district->district.'<br/>';
					if(!empty($additional_address_district[1])) {
						$additional_addresses .= '<em>'.$additional_address_district[1].'</em><br/>';
					}
				}
				//Fuer die alten Restbestaende bei denen noch keine Adressen zu den Bezirken gespeichert wurden
				else {
					$district = JTable::getInstance('districts','Table');
					$district->load($additional_address_district);
					$additional_addresses .= $district->zip.' '.$district->district.'<br/>';
				}
			}
		}
	  $additional_addresses .= '</div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
	  </div>
	</div>
  </div>
</div>';
	}
	echo $additional_addresses;
	?>
    </td>
	<td>
	<?php 
	echo '<strong>';
	echo !empty($this->order->salutation_name) ? $this->order->salutation_name : ''; 
	echo !empty($this->order->title_name) ? ' '.$this->order->title_name : ''; 
	echo ' '.$this->order->name.'</strong>';
	echo '<br/>';
	
	echo !empty($this->order->phone) ? ' '.$this->order->phone : ''; 
	echo !empty($this->order->email) ? '<br/>'.$this->order->email : ''; 
	?>
    </td>
    <td>
	<?php 
	$comment = '';
	if(!empty($this->order->comment)) 
	{
		$comment = '<button type="button" class="btn btn-default btn-in-datatable" data-toggle="modal" data-target="#commentModal'.$this->order->order_id.'"><span class="glyphicon glyphicon-comment"></span></button>';
		$comment .= '<div class="modal fade" id="commentModal'.$this->order->order_id.'" tabindex="-1" role="dialog" aria-labelledby="#commentModalLabel'.$this->order->order_id.'" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="commentModalLabel'.$this->order->order_id.'">Kommentar</h4>
      </div>
      <div class="modal-body">
        '.$this->order->comment.'
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
      </div>
    </div>
  </div>
</div>';
	}
    echo $comment;
	?>
    </td>
    <td>
	<?php 
	echo '€ '.$this->order->price;
	echo !empty($this->order->paymentmethod_name) ? '<br/>'.$this->order->paymentmethod_name : ''; 
	echo ($this->order->price_override) ? '<br/>Spezialpreis' : ''; 
	?>
    </td>
</tr>