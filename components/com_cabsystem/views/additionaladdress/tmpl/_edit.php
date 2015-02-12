<div class="modal fade" id="editAdditionaladdressModal" tabindex="-1" role="dialog" aria-labelledby="editAdditionaladdressModalLabel" aria-hidden="true">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

				<h4 class="modal-title" id="editAdditionaladdressModalLabel">Zusatzadresse bearbeiten</h4>

			</div>

			<div class="modal-body">

				<form id="editAdditionaladdressForm" class="form-horizontal" role="form">

					<div class="form-group">

						<label for="editForm-name" class="col-sm-2 control-label">Name</label>

						<div class="col-sm-10">

							<input type="text" class="form-control" id="editForm-name" name="name" value="<?php echo $this->additionaladdress->name; ?>">

						</div>

					</div>
                    
                    <div class="form-group">
                        <label for="editForm-districts" class="col-sm-2 control-label">Bezirke</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="editForm-districts" name="districts" value="<?php echo $this->additionaladdress->districts; ?>"/>
                        </div>
                    </div>
                    
					<?php
                        foreach($this->cartypes as $cartype) 
						{
							echo '<div class="form-group">';
							echo '<label for="addForm-cartype_price'.$cartype->cartype_id.'" class="col-sm-2 control-label">'.$cartype->name.'</label>';
							echo '<div class="col-sm-10">';
                            echo '<input class="form-control" type="text" id="addForm-cartype_price'.$cartype->cartype_id.'" name="addForm-cartype_price'.$cartype->cartype_id.'" value="'.$this->additionaladdress->cartype_prices[''.$cartype->cartype_id]['price'].'"/>';	
							if(!empty($this->additionaladdress->cartype_prices[''.$cartype->cartype_id]))
							{
								echo '<input type="hidden" name="editForm-cartype_price'.$cartype->cartype_id.'-id" value="'.$this->additionaladdress->cartype_prices[''.$cartype->cartype_id]['additionalprice_id'].'"/>';
							}
							echo '</div>';
							echo '</div>';
                        }
                      ?>
                    <input type="hidden" name="additionaladdress_id" value="<?php echo $this->additionaladdress->additionaladdress_id; ?>" />
                    <input type="hidden" name="view" value="additionaladdress" />
                    <input type="hidden" name="model" value="Additionaladdress" />
                    <input type="hidden" name="item" value="additionaladdress" />
                    <input type="hidden" name="table" value="additionaladdresses" />


			</div>

			<div class="modal-footer">

				<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>

				<button type="submit" class="btn btn-primary" id="editAdditionaladdress">Speichern</button>

				</form>
			</div>

		</div>

	</div>

</div>