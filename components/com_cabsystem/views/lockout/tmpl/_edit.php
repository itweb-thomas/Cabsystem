<div class="modal fade" id="editLockoutModal" tabindex="-1" role="dialog" aria-labelledby="editLockoutModalLabel" aria-hidden="true">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

				<h4 class="modal-title" id="editLockoutModalLabel">Sperre bearbeiten</h4>

			</div>

			<div class="modal-body">

				<form id="editLockoutForm" class="form-horizontal" role="form">

					<div class="form-group">
						<label for="editForm-date" class="col-md-2 control-label">Datum</label>
						<div class="col-md-10">
							<div class='input-group date' id='editForm-date-picker' data-date-format="YYYY-MM-DD" data-min-date="">
								<input type='text' class="form-control" name="date" id="editForm-date" readonly="readonly" value="<?php echo date("Y-m-d", strtotime($this->lockout->date)); ?>"/>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                </span>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label for="editForm-hour" class="col-md-2 control-label">Wann</label>

						<div class="col-md-10">
							<select class="form-control" name="hour" id="editForm-hour">
								<?php
								for($i=0;$i<24;$i++) {
									$selected = '';
									if($i == $this->lockout->hour) {
										$selected = 'selected="selected"';
									}
									$from = mktime($i, 0, 00, 1, 1, 1970);
									$to = mktime($i+1, 0, 00, 1, 1, 1970);
									echo '<option value="'.$i.'" '.$selected.'>'.(date("H:i", $from).' - '.date("H:i", $to)).'</option>';
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
									if($this->lockout->active == 1)
									{
										$checked = 'checked="checked"';
									}
									?>
									<input type="checkbox" id="editForm-active" name="active" <?php echo $checked;?>> aktiv

								</label>

							</div>

						</div>

					</div>
					<div class="alert alert-info" role="alert">
						<strong>Hinweis: </strong>Wenn mehrere Sperren für den gleichen Zeitpunkt gespeichert wurden zählt immer jene, die das System auch wirklich sperrt
					</div>
					<input type="hidden" name="created" value="<?php echo $this->lockout->created; ?>" />
                    <input type="hidden" name="lockout_id" value="<?php echo $this->lockout->lockout_id; ?>" />
                    <input type="hidden" name="view" value="lockout" />
                    <input type="hidden" name="model" value="Lockout" />
                    <input type="hidden" name="item" value="lockout" />
                    <input type="hidden" name="table" value="lockouts" />


			</div>

			<div class="modal-footer">

				<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>

				<button type="submit" class="btn btn-primary" id="editLockout">Speichern</button>

			</div>
			</form>

		</div>

	</div>

</div>