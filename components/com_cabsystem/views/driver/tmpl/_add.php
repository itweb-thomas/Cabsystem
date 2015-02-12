<div class="modal fade add-form" id="addDriverModal" tabindex="-1" role="dialog" aria-labelledby="addDriverModalLabel" aria-hidden="true">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

				<h4 class="modal-title" id="addDriverModalLabel">Fahrer erstellen</h4>

			</div>

			<div class="modal-body">

				<form id="addDriverForm" class="form-horizontal" role="form">

					<div class="form-group">

						<label for="addForm-name" class="col-sm-2 control-label">Name</label>

						<div class="col-sm-10">

							<input type="text" class="form-control" id="addForm-name" name="name">

						</div>

					</div>

					<div class="form-group">

						<label for="addForm-email" class="col-sm-2 control-label">Email</label>

						<div class="col-sm-10">

							<input type="text" class="form-control" id="addForm-email" name="email">

						</div>

					</div>

					<div class="form-group">

						<label for="addForm-cartype_id" class="col-sm-2 control-label">Autotyp</label>

						<div class="col-sm-10">

                            <select class="form-control" name="cartype_id" id="addForm-cartype_id">
                              <?php
                                foreach($this->cartypes as $cartype) {
                                    echo '<option value="'.$cartype->cartype_id.'">'.$cartype->name.'</option>';	
                                }
                              ?>
                            </select>

						</div>

					</div>

					<div class="form-group">

						<div class="col-sm-offset-2 col-sm-10">

							<div class="checkbox">

								<label>

									<input type="checkbox" id="addForm-active" name="active"> aktiv

								</label>

							</div>

						</div>

					</div> 
                    <input type="hidden" name="view" value="driver" />
                    <input type="hidden" name="model" value="Driver" />
                    <input type="hidden" name="item" value="driver" />
                    <input type="hidden" name="table" value="drivers" />


			</div>

			<div class="modal-footer">

				<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>

				<button type="button" class="btn btn-primary" id="addDriver">Speichern</button>

				</form>
			</div>

		</div>

	</div>

</div>