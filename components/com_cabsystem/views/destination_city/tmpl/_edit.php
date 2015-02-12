<div class="modal fade" id="editCityModal" tabindex="-1" role="dialog" aria-labelledby="editCityModalLabel" aria-hidden="true">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

				<h4 class="modal-title" id="editCityModalLabel">Abflugstadt bearbeiten</h4>

			</div>

			<div class="modal-body">

				<form id="editCityForm" class="form-horizontal" role="form">

					<div class="form-group">

						<label for="editForm-name" class="col-sm-2 control-label">Name</label>

						<div class="col-sm-10">

							<input type="text" class="form-control" id="editForm-name" name="name" value="<?php echo $this->destination_city->name; ?>">

						</div>

					</div>
                    <input type="hidden" name="city_id" value="<?php echo $this->destination_city->city_id; ?>" />
                    <input type="hidden" name="view" value="destination_city" />
                    <input type="hidden" name="model" value="Destination_city" />
                    <input type="hidden" name="item" value="destination_city" />
                    <input type="hidden" name="table" value="destination_cities" />


			</div>

			<div class="modal-footer">

				<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>

				<button type="submit" class="btn btn-primary" id="editCity">Speichern</button>

				</form>
			</div>

		</div>

	</div>

</div>