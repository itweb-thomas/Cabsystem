<div class="modal fade" id="editStreetModal" tabindex="-1" role="dialog" aria-labelledby="editStreetModalLabel" aria-hidden="true">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

				<h4 class="modal-title" id="editStreetModalLabel">Straße bearbeiten</h4>

			</div>

			<div class="modal-body">

				<form id="editStreetForm" class="form-horizontal" role="form">

					<div class="form-group">

						<label for="editForm-name" class="col-sm-2 control-label">Name</label>

						<div class="col-sm-10">

							<input type="text" class="form-control" id="editForm-name" name="name" value="<?php echo $this->street->name; ?>">

						</div>

					</div>

					<div class="form-group">

						<label for="editForm-district_id" class="col-sm-2 control-label">Bezirk</label>

						<div class="col-sm-10">

                            <select class="form-control" name="district_id" id="editForm-district_id">
                              <?php
                                foreach($this->districts as $district) 
								{
									$selected = "";
									if($this->street->district_id == $district->district_id) 
									{
										$selected = 'selected="selected"';
									}
									echo '<option value="'.$district->district_id.'" '.$selected.'>'.$district->zip.'-'.$district->district.'</option>';	
                                }
                              ?>
                            </select>

						</div>

					</div>
                    <input type="hidden" name="street_id" value="<?php echo $this->street->street_id; ?>" />
                    <input type="hidden" name="view" value="street" />
                    <input type="hidden" name="model" value="Street" />
                    <input type="hidden" name="item" value="street" />
                    <input type="hidden" name="table" value="streets" />


			</div>

			<div class="modal-footer">

				<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>

				<button type="submit" class="btn btn-primary" id="editStreet">Speichern</button>

				</form>
			</div>

		</div>

	</div>

</div>