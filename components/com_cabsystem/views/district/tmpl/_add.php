<div class="modal fade add-form" id="addDistrictModal" tabindex="-1" role="dialog" aria-labelledby="addDistrictModalLabel" aria-hidden="true">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

				<h4 class="modal-title" id="addDistrictModalLabel">Bezirk/Preis erstellen</h4>

			</div>

			<div class="modal-body">

				<form id="addDistrictForm" class="form-horizontal" role="form">

					<div class="panel panel-info">
                        <div class="panel-heading">Ort</div>
                        <div class="panel-body">
                        <div class="form-group">
    
                            <label for="addForm-zip" class="col-sm-2 control-label">Postleitzahl</label>
    
                            <div class="col-sm-10">
    
                                <input type="text" class="form-control" id="addForm-zip" name="zip">
    
                            </div>
    
                        </div>
                        
                        <div class="form-group">
    
                            <label for="addForm-district" class="col-sm-2 control-label">Name</label>
    
                            <div class="col-sm-10">
    
                                <input type="text" class="form-control" id="addForm-district" name="district">
    
                            </div>
    
                        </div>
                        
                        <div class="form-group">
    
                            <label for="addForm-city_id" class="col-sm-2 control-label">Stadt</label>
    
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
                            echo '<input class="form-control" type="text" id="addForm-cartype_price'.$cartype->cartype_id.'" name="addForm-cartype_price'.$cartype->cartype_id.'"/>';	
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
                            echo '<input class="form-control" type="text" id="addForm-cartype_price_additional_address'.$cartype->cartype_id.'" name="addForm-cartype_price_additional_address'.$cartype->cartype_id.'"/>';	
							echo '</div>';
							echo '</div>';
                        }
						?>
                        </div>
					</div>
                    <input type="hidden" name="view" value="district" />
                    <input type="hidden" name="model" value="District" />
                    <input type="hidden" name="item" value="district" />
                    <input type="hidden" name="table" value="districts" />


			</div>

			<div class="modal-footer">

				<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>

				<button type="button" class="btn btn-primary" id="addDistrict">Speichern</button>

				</form>
			</div>

		</div>

	</div>

</div>