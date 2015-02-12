<div class="modal fade" id="editDistrictModal" tabindex="-1" role="dialog" aria-labelledby="editDistrictModalLabel" aria-hidden="true">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

				<h4 class="modal-title" id="editDistrictModalLabel">Bezirk/Preis bearbeiten</h4>

			</div>

			<div class="modal-body">

				<form id="editDistrictForm" class="form-horizontal" role="form">

					<div class="panel panel-info">
                        <div class="panel-heading">Ort</div>
                        <div class="panel-body">
                        <div class="form-group">
    
                            <label for="editForm-zip" class="col-sm-2 control-label">Postleitzahl</label>
    
                            <div class="col-sm-10">
    
                                <input type="text" class="form-control" id="editForm-zip" name="zip" value="<?php echo $this->district->zip; ?>">
    
                            </div>
    
                        </div>
                        <div class="form-group">
    
                            <label for="editForm-district" class="col-sm-2 control-label">Name</label>
    
                            <div class="col-sm-10">
    
                                <input type="text" class="form-control" id="editForm-district" name="district" value="<?php echo $this->district->district; ?>">
    
                            </div>
    
                        </div>
                        
                        <div class="form-group">
    
                            <label for="editForm-city_id" class="col-sm-2 control-label">Stadt</label>
    
                            <div class="col-sm-10">
    
                                <select class="form-control" name="city_id" id="editForm-city_id">
                                  <?php
                                    foreach($this->cities as $city) 
                                    {
                                        $selected = "";
                                        if($this->district->city_id == $city->city_id) 
                                        {
                                            $selected = 'selected="selected"';
                                        }
                                        echo '<option value="'.$city->city_id.'" '.$selected.'>'.$city->name.'</option>';	
                                    }
                                  ?>
                                </select>
    
                            </div>
    
                        </div>
                    	</div>
                    </div>
                    
					<div class="panel panel-info">
                        <div class="panel-heading">Normale Preise</div>
                        <div class="panel-body">
						<?php
                            foreach($this->cartypes as $cartype) 
                            {
                                echo '<div class="form-group">';
                                echo '<label for="addForm-cartype_price'.$cartype->cartype_id.'" class="col-sm-2 control-label">'.$cartype->name.'</label>';
                                echo '<div class="col-sm-10">';
                                $value = '';
                                if($this->district->cartype_prices[''.$cartype->cartype_id]['price']>=0) {
                                    $value = $this->district->cartype_prices[''.$cartype->cartype_id]['price'];
                                }
                                echo '<input class="form-control" type="text" id="addForm-cartype_price'.$cartype->cartype_id.'" name="addForm-cartype_price'.$cartype->cartype_id.'" value="'.$value.'"/>';	
                                if(!empty($this->district->cartype_prices[''.$cartype->cartype_id]))
                                {
                                    echo '<input type="hidden" name="editForm-cartype_price'.$cartype->cartype_id.'-id" value="'.$this->district->cartype_prices[''.$cartype->cartype_id]['price_id'].'"/>';
                                }
                                echo '</div>';
                                echo '</div>';
                            }
                          ?>
						</div>
					</div>
                    <div class="panel panel-info">
                        <div class="panel-heading">Preise für Zusatzadressen</div>
                        <div class="panel-body">
                        <?php
							foreach($this->cartypes as $cartype) 
							{
								echo '<div class="form-group">';
								echo '<label for="addForm-cartype_price_additional_address'.$cartype->cartype_id.'" class="col-sm-2 control-label">'.$cartype->name.'</label>';
								echo '<div class="col-sm-10">';
								$value = '';
								if($this->district->cartype_prices_additional_address[''.$cartype->cartype_id]['additional_address_price']>=0) {
									$value = $this->district->cartype_prices_additional_address[''.$cartype->cartype_id]['additional_address_price'];
								}
								echo '<input class="form-control" type="text" id="addForm-cartype_price_additional_address'.$cartype->cartype_id.'" name="addForm-cartype_price_additional_address'.$cartype->cartype_id.'" value="'.$value.'"/>';	
								if(!empty($this->district->cartype_prices_additional_address[''.$cartype->cartype_id]))
								{
									echo '<input type="hidden" name="editForm-cartype_price_additional_address'.$cartype->cartype_id.'-id" value="'.$this->district->cartype_prices_additional_address[''.$cartype->cartype_id]['price_id'].'"/>';
								}
								echo '</div>';
								echo '</div>';
							}
						  ?>
						</div>
					</div>
                    <input type="hidden" name="district_id" value="<?php echo $this->district->district_id; ?>" />
                    <input type="hidden" name="view" value="district" />
                    <input type="hidden" name="model" value="District" />
                    <input type="hidden" name="item" value="district" />
                    <input type="hidden" name="table" value="districts" />


			</div>

			<div class="modal-footer">

				<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>

				<button type="submit" class="btn btn-primary" id="editDistrict">Speichern</button>

				</form>
			</div>

		</div>

	</div>

</div>