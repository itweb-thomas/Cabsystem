<div class="modal fade add-form" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="addCustomerModalLabel" aria-hidden="true">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

				<h4 class="modal-title" id="addCustomerModalLabel">Kunde erstellen</h4>

			</div>

			<div class="modal-body">
            
            	<?php
				$district_array = array();
				$street_array = array();
				foreach($this->cities as $city) 
				{
					$district_array[$city->city_id] = array();
					foreach($city->districts as $district) 
					{
						array_push($district_array[$city->city_id],array('id'=>$district->district_id,'tag'=>$district->district));
						
						$street_array[$district->district_id] = array();
						foreach($district->streets as $street) 
						{
							array_push($street_array[$district->district_id],array('id'=>$street->street_id,'tag'=>$street->name));
						}
					}
				}
				echo '<input id="addForm-district-array" type="hidden" value="'.htmlentities(json_encode($district_array)).'"/>';
				echo '<input id="addForm-street-array" type="hidden" value="'.htmlentities(json_encode($street_array)).'"/>';
				?>

				<form id="addCustomerForm" class="form-horizontal" role="form">

					<div class="form-group">

						<label for="addForm-salutation_id" class="col-sm-2 control-label">Anrede</label>

						<div class="col-sm-10">

                            <select class="form-control" name="salutation_id" id="addForm-salutation_id">
                              <?php
                                foreach($this->salutations as $salutation) {
                                    echo '<option value="'.$salutation->salutation_id.'">'.JText::_($salutation->language_string).'</option>';
                                }
                              ?>
                            </select>

						</div>

					</div>
                    
                    <div class="form-group">

						<label for="addForm-title_id" class="col-sm-2 control-label">Titel</label>

						<div class="col-sm-10">

                            <select class="form-control" name="title_id" id="addForm-title_id">
                              <?php
							  	echo '<option value=""></option>';	
                                foreach($this->titles as $title) {
                                    echo '<option value="'.$title->title_id.'">'.$title->name.'</option>';	
                                }
                              ?>
                            </select>

						</div>

					</div>

					<div class="form-group">

						<label for="addForm-name" class="col-sm-2 control-label">Name</label>

						<div class="col-sm-10">

							<input type="text" class="form-control" id="addForm-name" name="name">

						</div>

					</div>

					<div class="form-group">

						<label for="addForm-city_id" class="col-sm-2 control-label">Ort</label>

						<div class="col-sm-10">

                            <select class="form-control" name="city_id" id="addForm-city_id">
                              <?php
                                foreach($this->cities as $city) {
                                    echo '<option value="'.$city->city_id.'">'.$city->name.'</option>';	
                                }
                              ?>
                            </select>

						</div>

					</div>

					<div class="form-group">

						<label for="addForm-district_id" class="col-sm-2 control-label">Bezirk</label>

						<div class="col-sm-10">
                            <input type='hidden' class="form-control" name="district_id" id="addForm-district_id" />
						</div>

					</div>

					<div class="form-group">

						<label for="addForm-street_id" class="col-sm-2 control-label">Straße</label>

						<div class="col-sm-10">
                            <input type='hidden' class="form-control" name="street_id" id="addForm-street_id" />

						</div>

					</div>
					<div class="form-group">
						<label for="addForm-house" class="col-sm-2 control-label">Zusatz</label>

						<div class="col-sm-10 row">
                        	<div class="col-sm-4">
                            	<input type='text' class="form-control" name="house" id="addForm-house" placeholder="Hausnummer"/>
                            </div>
                            <div class="col-sm-4">
                                <input type='text' class="form-control" name="stair" id="addForm-stair" placeholder="Stiege"/>
                            </div>
                            <div class="col-sm-4">
                                <input type='text' class="form-control" name="door" id="addForm-door" placeholder="Tür"/>
                            </div>
						</div>
					</div>
                    
                    <div class="form-group">

						<label for="addForm-phone" class="col-sm-2 control-label">Telefon</label>

						<div class="col-sm-10">

							<input type="text" class="form-control" id="addForm-phone" name="phone">

						</div>

					</div>

					<div class="form-group">

						<label for="addForm-email" class="col-sm-2 control-label">Email</label>

						<div class="col-sm-10">

							<input type="text" class="form-control" id="addForm-email" name="email">

						</div>

					</div>
                    <input type="hidden" name="view" value="customer" />
                    <input type="hidden" name="model" value="Customer" />
                    <input type="hidden" name="item" value="customer" />
                    <input type="hidden" name="table" value="customers" />


			</div>

			<div class="modal-footer">

				<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>

				<button type="button" class="btn btn-primary" id="addCustomer">Speichern</button>

				</form>
			</div>

		</div>

	</div>

</div>