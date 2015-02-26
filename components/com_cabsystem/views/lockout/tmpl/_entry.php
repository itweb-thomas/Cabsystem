<tr class="listRow" data-lockout_id="<?php echo $this->lockout->lockout_id; ?>">
	<td>
		<?php
		$from = mktime($this->lockout->hour, 0, 00, 1, 1, 1970);
		$to = mktime($this->lockout->hour+1, 0, 00, 1, 1, 1970);
		echo date("H:i", $from).' - '.date("H:i", $to);
		?>
	</td>
	<td>
	<?php 
	$active = '<i class="icon-sm icon-inactive fa fa-minus-circle"></i>';
	if($this->lockout->active == 0)
	{
		$active = '<i class="icon-sm icon-active fa fa-check-circle"></i>';
	}
    echo $active;
	?>
    </td>
</tr>