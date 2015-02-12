<tr class="listRow" data-driver_id="<?php echo $this->driver->driver_id; ?>">
	<td>
	<?php 
	$active = '<i class="icon-sm icon-inactive fa fa-minus-circle"></i>';
	if($this->driver->active == 1) 
	{
		$active = '<i class="icon-sm icon-active fa fa-check-circle"></i>';
	}
    echo $active;
	?>
    </td>
	<td><?php echo $this->driver->name; ?></td>
	<td><?php echo $this->driver->email; ?></td>
	<td><?php echo $this->driver->cartype_name; ?></td>
</tr>