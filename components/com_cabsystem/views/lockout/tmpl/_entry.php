<tr class="listRow" data-lockout_id="<?php echo $this->lockout->lockout_id; ?>">
	<td>
		<?php
		echo strtotime($this->lockout->date);
		?>
	</td>
	<td>
		<?php
			echo date("d.m.Y", strtotime($this->lockout->date));
		?>
	</td>
	<td>
		<?php
		$from = mktime($this->lockout->hour, 0, 00, 1, 1, 1970);
		$to = mktime($this->lockout->hour+1, 0, 00, 1, 1, 1970);
		echo date("H:i", $from).' - '.date("H:i", $to);
		?>
	</td>
	<td>
	<?php 
	$active = '<i class="icon-sm icon-inactive fa fa-minus-circle"></i>&nbsp;System ist gesperrt';
	if($this->lockout->active == 0)
	{
		$active = '<i class="icon-sm icon-active fa fa-check-circle"></i>&nbsp;System ist frei';
	}
    echo $active;
	?>
    </td>
</tr>