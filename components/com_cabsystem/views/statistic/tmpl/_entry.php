<tr class="listRow" data-order_id="<?php echo $this->order->order_id; ?>" data-driver_id="<?php echo $this->order->driver_id; ?>">
<td><?php echo $this->order->order_id; ?></td>
<td><?php echo $this->order->driver_id; ?></td>
<td>
<?php 
echo JText::_(date("n", strtotime($this->order->datetime))); 
?>
</td>
<td>
<?php 
echo JText::_(date("Y", strtotime($this->order->datetime))); 
?>
</td>
<td><?php echo JText::_('COM_CABSYSTEM_ORDER_STATUS'.$this->order->status);?></td>
<td>
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
    //abgelehnt
    case 3:
        $status = '<i class="icon-inactive fa fa-minus-circle fa-2x"></i>';
    break;
    default:
        $status_textclass = '';
        $status = '<i class="fa fa-question fa-2x"></i>';
}
echo $status;
?>
</td>
<td><?php echo '<strong>'.date("d.m.Y", strtotime($this->order->datetime)).'</strong><br/>'.date("H:i", strtotime($this->order->datetime)); ?></td>
<td>
<?php 
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
			echo $this->order->flight_number.' '.$this->order->destionation_city_name.' '.$this->order->flight_time;
		}
?>
</td>
<td>
<?php 
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
if(!empty($this->order->additionaladdresses_id)) {
	$additional_addresses = '<button type="button" class="btn btn-default btn-in-datatable" data-toggle="modal" data-target="#additionaladdressesModal'.$this->order->additionaladdresses_id.'">'.$this->order->additional_address_districts_amount.'</button>';
	$additional_addresses .= '<div class="modal fade" id="additionaladdressesModal'.$this->order->additionaladdresses_id.'" tabindex="-1" role="dialog" aria-labelledby="#additionaladdressesModalLabel'.$this->order->additionaladdresses_id.'" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
  <div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title" id="commentModalLabel'.$this->order->order_id.'">Zusatzadressen</h4>
  </div>
  <div class="modal-body">';
  
	if(!empty($this->order->additional_address_districts)) {
		foreach(json_decode($this->order->additional_address_districts) as $additional_address_district) {
			$district = JTable::getInstance('districts','Table');
			$district->load($additional_address_district);
			$additional_addresses .= $district->zip.' '.$district->district.'<br/>';
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
echo $this->order->cartype_name;
echo !empty($this->order->luggage) ? '<br/><strong>'.$this->order->luggage.' Koffer</strong>' : '<br/><strong>0 Koffer</strong>'; 
echo !empty($this->order->handluggage) ? '<br/><strong>'.$this->order->handluggage.' Handgep.</strong>' : '<br/><strong>0 Handgep.</strong>'; 
echo !empty($this->order->driver_name) ? '<br/><strong class="text-active">'.$this->order->driver_name.'</strong>' : '<br/><strong class="text-inactive">kein Fahrer</strong>'; 
?>
</td>
<td>
<?php 
echo '€ '.$this->order->price;
echo !empty($this->order->paymentmethod_name) ? '<br/>'.$this->order->paymentmethod_name : ''; 
?>
</td>
<td>
<?php 
echo $this->order->price;
?>
</td>
</tr>