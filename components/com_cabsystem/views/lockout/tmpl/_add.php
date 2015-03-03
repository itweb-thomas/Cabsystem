<div class="modal fade add-form" id="addLockoutModal" tabindex="-1" role="dialog" aria-labelledby="addLockoutModalLabel" aria-hidden="true">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

				<h4 class="modal-title" id="addLockoutModalLabel">Sperre erstellen</h4>

			</div>

			<div class="modal-body">

				<form id="addLockoutForm" class="form-horizontal" role="form">

					<div class="form-group">
						<label for="addForm-date" class="col-md-2 control-label">Datum</label>
						<div class="col-md-10">
							<div class='input-group date' id='addForm-date-picker' data-date-format="YYYY-MM-DD" data-min-date="">
								<input type='text' class="form-control" name="date" id="addForm-date" readonly="readonly"/>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                </span>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label for="addForm-hour" class="col-md-2 control-label">Wann</label>

						<div class="col-md-10">
							<select class="form-control" name="hour" id="addForm-hour">
								<?php
								for($i=0;$i<24;$i++) {
									$from = mktime($i, 0, 00, 1, 1, 1970);
									$to = mktime($i+1, 0, 00, 1, 1, 1970);
									echo '<option value="'.$i.'">'.(date("H:i", $from).' - '.date("H:i", $to)).'</option>';
								}
								?>
							</select>
						</div>
					</div>

					<div class="form-group">

						<div class="col-sm-offset-2 col-md-10">

							<div class="checkbox">

								<label>

									<input type="checkbox" id="addForm-active" name="active"> System zu diesem Zeitpunkt sperren

								</label>

							</div>

						</div>

					</div> 
                    <input type="hidden" name="view" value="lockout" />
                    <input type="hidden" name="model" value="Lockout" />
                    <input type="hidden" name="item" value="lockout" />
                    <input type="hidden" name="table" value="lockouts" />


			</div>

			<div class="modal-footer">

				<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>

				<button type="button" class="btn btn-primary" id="addLockout">Speichern</button>

				</form>
			</div>

		</div>

	</div>

</div>