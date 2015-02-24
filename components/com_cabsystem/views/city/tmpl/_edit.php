<div class="modal fade" id="editCityModal" tabindex="-1" role="dialog" aria-labelledby="editCityModalLabel" aria-hidden="true">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

				<h4 class="modal-title" id="editCityModalLabel">Wohnort bearbeiten</h4>

			</div>

			<div class="modal-body">

				<form id="editCityForm" class="form-horizontal" role="form">

					<div class="form-group">

						<label for="editForm-name" class="col-sm-2 control-label">Name</label>

						<div class="col-sm-10">

							<input type="text" class="form-control" id="editForm-name" name="name" value="<?php echo $this->city->name; ?>">

						</div>

					</div>
					<input type="hidden" name="created" value="<?php echo $this->city->created; ?>" />
                    <input type="hidden" name="city_id" value="<?php echo $this->city->city_id; ?>" />
                    <input type="hidden" name="view" value="city" />
                    <input type="hidden" name="model" value="City" />
                    <input type="hidden" name="item" value="city" />
                    <input type="hidden" name="table" value="cities" />


			</div>

			<div class="modal-footer">

				<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>

				<button type="submit" class="btn btn-primary" id="editCity">Speichern</button>

				</form>
			</div>

		</div>

	</div>

</div>