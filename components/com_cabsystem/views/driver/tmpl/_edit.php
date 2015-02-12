<div class="modal fade" id="editDriverModal" tabindex="-1" role="dialog" aria-labelledby="editDriverModalLabel" aria-hidden="true">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

				<h4 class="modal-title" id="editDriverModalLabel">Fahrer bearbeiten</h4>

			</div>

			<div class="modal-body">

				<form id="editDriverForm" class="form-horizontal" role="form">

					<div class="form-group">

						<label for="editForm-name" class="col-sm-2 control-label">Name</label>

						<div class="col-sm-10">

							<input type="text" class="form-control" id="editForm-name" name="name" value="<?php echo $this->driver->name; ?>">

						</div>

					</div>

					<div class="form-group">

						<label for="editForm-email" class="col-sm-2 control-label">Email</label>

						<div class="col-sm-10">

							<input type="text" class="form-control" id="editForm-email" name="email" value="<?php echo $this->driver->email; ?>">

						</div>

					</div>

					<div class="form-group">

						<label for="editForm-cartype_id" class="col-sm-2 control-label">Autotyp</label>

						<div class="col-sm-10">

                            <select class="form-control" name="cartype_id" id="editForm-cartype_id">
                              <?php
                                foreach($this->cartypes as $cartype) 
								{
									$selected = "";
									if($this->driver->cartype_id == $cartype->cartype_id) 
									{
										$selected = 'selected="selected"';
									}
                                    echo '<option value="'.$cartype->cartype_id.'" '.$selected.'>'.$cartype->name.'</option>';	
                                }
                              ?>
                            </select>

						</div>

					</div>

					<div class="form-group">

						<div class="col-sm-offset-2 col-sm-10">

							<div class="checkbox">

								<label>
									<?php
									$checked = "";
									if($this->driver->active) 
									{
										$checked = 'checked="checked"';
									}
									?>
									<input type="checkbox" id="editForm-active" name="active" <?php echo $checked;?>> aktiv

								</label>

							</div>

						</div>

					</div> 
                    <input type="hidden" name="driver_id" value="<?php echo $this->driver->driver_id; ?>" />
                    <input type="hidden" name="view" value="driver" />
                    <input type="hidden" name="model" value="Driver" />
                    <input type="hidden" name="item" value="driver" />
                    <input type="hidden" name="table" value="drivers" />


			</div>

			<div class="modal-footer">

				<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>

				<button type="submit" class="btn btn-primary" id="editDriver">Speichern</button>

				</form>
			</div>

		</div>

	</div>

</div>