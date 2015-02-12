<div class="modal fade add-form" id="addFlightModal" tabindex="-1" role="dialog" aria-labelledby="addFlightModalLabel" aria-hidden="true">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

				<h4 class="modal-title" id="addFlightModalLabel">Flug erstellen</h4>

			</div>

			<div class="modal-body">

				<form id="addFlightForm" class="form-horizontal" role="form">

					<div class="form-group">

						<label for="addForm-flightnumber" class="col-sm-2 control-label">Flugnummer</label>

						<div class="col-sm-10">

							<input type="text" class="form-control" id="addForm-flightnumber" name="flightnumber">

						</div>

					</div>

					<div class="form-group">

						<label for="addForm-time" class="col-sm-2 control-label">Zeit</label>

						<div class="col-sm-10">

							<input type="text" class="form-control" id="addForm-time" name="time">

						</div>

					</div>

					<div class="form-group">

						<label for="addForm-city_id" class="col-sm-2 control-label">Stadt</label>

						<div class="col-sm-10">

                            <select class="form-control" name="city_id" id="addForm-city_id">
                              <?php
                                foreach($this->cities as $city) {
                                    echo '<option value="'.$city->city_id.'">'.$city->name.'</option>';	
                                }
                              ?>
                            </select>

						</div>

					</div>
                    <input type="hidden" name="view" value="flight" />
                    <input type="hidden" name="model" value="Flight" />
                    <input type="hidden" name="item" value="flight" />
                    <input type="hidden" name="table" value="flights" />


			</div>

			<div class="modal-footer">

				<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>

				<button type="button" class="btn btn-primary" id="addFlight">Speichern</button>

				</form>
			</div>

		</div>

	</div>

</div>