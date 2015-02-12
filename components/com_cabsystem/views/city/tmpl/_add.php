<div class="modal fade add-form" id="addCityModal" tabindex="-1" role="dialog" aria-labelledby="addCityModalLabel" aria-hidden="true">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

				<h4 class="modal-title" id="addCityModalLabel">Wohnort erstellen</h4>

			</div>

			<div class="modal-body">

				<form id="addCityForm" class="form-horizontal" role="form">

					<div class="form-group">

						<label for="addForm-name" class="col-sm-2 control-label">Name</label>

						<div class="col-sm-10">

							<input type="text" class="form-control" id="addForm-name" name="name">

						</div>

					</div>
                    <input type="hidden" name="view" value="city" />
                    <input type="hidden" name="model" value="City" />
                    <input type="hidden" name="item" value="city" />
                    <input type="hidden" name="table" value="cities" />


			</div>

			<div class="modal-footer">

				<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>

				<button type="button" class="btn btn-primary" id="addCity">Speichern</button>

				</form>
			</div>

		</div>

	</div>

</div>