<div class="modal fade" id="editFlightModal" tabindex="-1" role="dialog" aria-labelledby="editFlightModalLabel" aria-hidden="true">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

				<h4 class="modal-title" id="editFlightModalLabel">Flugnummer bearbeiten</h4>

			</div>

			<div class="modal-body">

				<form id="editFlightForm" class="form-horizontal" role="form">

					<div class="form-group">

						<label for="editForm-flightnumber" class="col-sm-2 control-label">Flugnummer</label>

						<div class="col-sm-10">

							<input type="text" class="form-control" id="editForm-flightnumber" name="flightnumber" value="<?php echo $this->flight->flightnumber; ?>">

						</div>

					</div>

					<div class="form-group">

						<label for="editForm-time" class="col-sm-2 control-label">Zeit</label>

						<div class="col-sm-10">

							<input type="text" class="form-control" id="editForm-time" name="time" value="<?php echo $this->flight->time; ?>">

						</div>

					</div>

					<div class="form-group">

						<label for="editForm-city_id" class="col-sm-2 control-label">Stadt</label>

						<div class="col-sm-10">

                            <select class="form-control" name="city_id" id="editForm-city_id">
                              <?php
                                foreach($this->cities as $city) 
								{
									$selected = "";
									if($this->flight->city_id == $city->city_id) 
									{
										$selected = 'selected="selected"';
									}
                                    echo '<option value="'.$city->city_id.'" '.$selected.'>'.$city->name.'</option>';	
                                }
                              ?>
                            </select>

						</div>

					</div>
					<input type="hidden" name="created" value="<?php echo $this->flight->created; ?>" />
                    <input type="hidden" name="flight_id" value="<?php echo $this->flight->flight_id; ?>" />
                    <input type="hidden" name="view" value="flight" />
                    <input type="hidden" name="model" value="Flight" />
                    <input type="hidden" name="item" value="flight" />
                    <input type="hidden" name="table" value="flights" />


			</div>

			<div class="modal-footer">

				<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>

				<button type="submit" class="btn btn-primary" id="editFlight">Speichern</button>

				</form>
			</div>

		</div>

	</div>

</div>