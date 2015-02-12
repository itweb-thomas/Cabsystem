<div class="modal fade add-form" id="addAdditionaladdressModal" tabindex="-1" role="dialog" aria-labelledby="addAdditionaladdressModalLabel" aria-hidden="true">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

				<h4 class="modal-title" id="addAdditionaladdressModalLabel">Zusatzadresse erstellen</h4>

			</div>

			<div class="modal-body">

				<form id="addAdditionaladdressForm" class="form-horizontal" role="form">

					<div class="form-group">
						<label for="addForm-name" class="col-sm-2 control-label">Name</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="addForm-name" name="name">
						</div>
					</div>
                    
                    <div class="form-group">
                        <label for="addForm-districts" class="col-sm-2 control-label">Bezirke</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="addForm-districts" name="districts"/>
                        </div>
                    </div>

					  <?php
                        foreach($this->cartypes as $cartype)
						{
							echo '<div class="form-group">';
							echo '<label for="addForm-cartype_price'.$cartype->cartype_id.'" class="col-sm-2 control-label">'.$cartype->name.'</label>';
							echo '<div class="col-sm-10">';
                            echo '<input class="form-control" type="text" id="addForm-cartype_price'.$cartype->cartype_id.'" name="addForm-cartype_price'.$cartype->cartype_id.'"/>';	
							echo '</div>';
							echo '</div>';
                        }
                      ?>
                    <input type="hidden" name="view" value="additionaladdress" />
                    <input type="hidden" name="model" value="Additionaladdress" />
                    <input type="hidden" name="item" value="additionaladdress" />
                    <input type="hidden" name="table" value="additionaladdresses" />


			</div>

			<div class="modal-footer">

				<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>

				<button type="button" class="btn btn-primary" id="addAdditionaladdress">Speichern</button>

				</form>
			</div>

		</div>

	</div>

</div>