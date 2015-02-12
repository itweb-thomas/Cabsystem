<div class="modal fade" id="setDriverModal" tabindex="-1" role="dialog" aria-labelledby="setDriverModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="setDriverModalLabel">Fahrer zuweisen</h4>
      </div>
      <div class="modal-body">
        <p>Wenn Sie einen Fahrer zuweisen wird die Bestellung auf "wartend" gesetzt bis der Fahrer die Zuordnung bestätigt oder ablehnt. Wenn Sie "kein Fahrer" auswählen wird die Bestellung zurück auf "neu" gesetzt und kann zu einem späteren Zeitpunkt zugewiesen werden.</p>
        <div id="driverInfo" class="alert alert-info" role="alert">Zu dieser Bestellung wurde schon ein Fahrer hinzugefügt. Wenn Sie einen anderen Fahrer auswählen, werden beide per Email davon informiert.</div>
        <div class="form-group">
            <label for="setDriverForm-driver_id" class="col-sm-2 control-label">Fahrer</label>
            <div class="col-sm-10">
                <select class="form-control" name="from_ordertype_id" id="setDriverForm-driver_id">
                  <?php
                    echo '<option value="">Kein Fahrer</option>';
                    foreach($this->drivers as $driver) {
						$selected = "";
						if($this->order->driver_id == $driver->driver_id)
						{
							$selected = 'selected="selected"';
						}
						
                        echo '<option value="'.$driver->driver_id.'" '.$selected.'>'.$driver->name.'</option>';	
                    }
                  ?>
                </select>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
        <button type="button" class="btn btn-primary setDriver" data-type="modal">Zuweisen und Email senden</button>
      </div>
    </div>
  </div>
</div>