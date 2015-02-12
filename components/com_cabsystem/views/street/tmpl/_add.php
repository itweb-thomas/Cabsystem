<div class="modal fade add-form" id="addStreetModal" tabindex="-1" role="dialog" aria-labelledby="addStreetModalLabel" aria-hidden="true">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

				<h4 class="modal-title" id="addStreetModalLabel">Straße erstellen</h4>

			</div>

			<div class="modal-body">

				<form id="addStreetForm" class="form-horizontal" role="form">

					<div class="form-group">

						<label for="addForm-name" class="col-sm-2 control-label">Name</label>

						<div class="col-sm-10">

							<input type="text" class="form-control" id="addForm-name" name="name">

						</div>

					</div>

					<div class="form-group">

						<label for="addForm-district_id" class="col-sm-2 control-label">Bezirk</label>

						<div class="col-sm-10">

                            <select class="form-control" name="district_id" id="addForm-district_id">
                              <?php
                                foreach($this->districts as $district) {
                                    echo '<option value="'.$district->district_id.'">'.$district->zip.'-'.$district->district.'</option>';	
                                }
                              ?>
                            </select>

						</div>

					</div>
                    <input type="hidden" name="view" value="street" />
                    <input type="hidden" name="model" value="Street" />
                    <input type="hidden" name="item" value="street" />
                    <input type="hidden" name="table" value="streets" />


			</div>

			<div class="modal-footer">

				<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>

				<button type="button" class="btn btn-primary" id="addStreet">Speichern</button>

				</form>
			</div>

		</div>

	</div>

</div>