<div class="modal fade" id="editCustomerModal" tabindex="-1" role="dialog" aria-labelledby="editCustomerModalLabel" aria-hidden="true">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

				<h4 class="modal-title" id="editCustomerModalLabel">Kunde bearbeiten</h4>

			</div>

			<div class="modal-body">

				<form id="editCustomerForm" class="form-horizontal" role="form">

					<div class="form-group">

						<label for="editForm-salutation_id" class="col-sm-2 control-label">Anrede</label>

						<div class="col-sm-10">

                            <select class="form-control" name="salutation_id" id="editForm-salutation_id">
                              <?php
                                foreach($this->salutations as $salutation) 
								{
									$selected = "";
									if($this->customer->salutation_id == $salutation->salutation_id) 
									{
										$selected = 'selected="selected"';
									}
                                    echo '<option value="'.$salutation->salutation_id.'" '.$selected.'>'.JText::_($salutation->language_string).'</option>';
                                }
                              ?>
                            </select>

						</div>

					</div>

					<div class="form-group">

						<label for="editForm-title_id" class="col-sm-2 control-label">Titel</label>

						<div class="col-sm-10">

                            <select class="form-control" name="title_id" id="editForm-title_id">
                              <?php
							  	echo '<option value=""></option>';	
                                foreach($this->titles as $title) 
								{
									$selected = "";
									if($this->customer->title_id == $title->title_id) 
									{
										$selected = 'selected="selected"';
									}
                                    echo '<option value="'.$title->title_id.'" '.$selected.'>'.$title->name.'</option>';	
                                }
                              ?>
                            </select>

						</div>

					</div>

					<div class="form-group">

						<label for="editForm-name" class="col-sm-2 control-label">Name</label>

						<div class="col-sm-10">

							<input type="text" class="form-control" id="editForm-name" name="name" value="<?php echo $this->customer->name; ?>">

						</div>

					</div>

					<div class="form-group">

						<label for="editForm-city_id" class="col-sm-2 control-label">Ort</label>

						<div class="col-sm-10">

                            <select class="form-control" name="city_id" id="editForm-city_id">
                              <?php
                                foreach($this->cities as $city) 
								{
									$selected = "";
									if($this->customer->city_id == $city->city_id) 
									{
										$selected = 'selected="selected"';
									}
									echo '<option value="'.$city->city_id.'" '.$selected.'>'.$city->name.'</option>';	
                                }
                              ?>
                            </select>

						</div>

					</div>

					<div class="form-group">

						<label for="editForm-district_id" class="col-sm-2 control-label">Bezirk</label>

						<div class="col-sm-10">
                            <input type='hidden' class="form-control" name="district_id" id="editForm-district_id" value="<?php echo $this->customer->district_id;?>"/>
						</div>

					</div>

					<div class="form-group">

						<label for="editForm-street_id" class="col-sm-2 control-label">Straße</label>

						<div class="col-sm-10">
                            <input type='hidden' class="form-control" name="street_id" id="editForm-street_id" value="<?php echo $this->customer->street_id;?>"/>
						</div>

					</div>
                    
					<div class="form-group">
						<label for="editForm-house" class="col-sm-2 control-label">Zusatz</label>

						<div class="col-sm-10 row">
                        	<div class="col-sm-4">
                            	<input type='text' class="form-control" name="house" id="editForm-house" placeholder="Hausnummer" value="<?php echo $this->customer->house;?>"/>
                            </div>
                            <div class="col-sm-4">
                                <input type='text' class="form-control" name="stair" id="editForm-stair" placeholder="Stiege" value="<?php echo $this->customer->stair;?>"/>
                            </div>
                            <div class="col-sm-4">
                                <input type='text' class="form-control" name="door" id="editForm-door" placeholder="Tür" value="<?php echo $this->customer->door;?>"/>
                            </div>
						</div>
					</div>

					<div class="form-group">

						<label for="editForm-phone" class="col-sm-2 control-label">Telefon</label>

						<div class="col-sm-10">

							<input type="text" class="form-control" id="editForm-phone" name="phone" value="<?php echo $this->customer->phone; ?>">

						</div>

					</div>

					<div class="form-group">

						<label for="editForm-email" class="col-sm-2 control-label">Email</label>

						<div class="col-sm-10">

							<input type="text" class="form-control" id="editForm-email" name="email" value="<?php echo $this->customer->email; ?>">

						</div>

					</div>
					<input type="hidden" name="created" value="<?php echo $this->customer->created; ?>" />
                    <input type="hidden" name="customer_id" value="<?php echo $this->customer->customer_id; ?>" />
                    <input type="hidden" name="view" value="customer" />
                    <input type="hidden" name="model" value="Customer" />
                    <input type="hidden" name="item" value="customer" />
                    <input type="hidden" name="table" value="customers" />


			</div>

			<div class="modal-footer">

				<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>

				<button type="submit" class="btn btn-primary" id="editCustomer">Speichern</button>

				</form>
			</div>

		</div>

	</div>

</div>