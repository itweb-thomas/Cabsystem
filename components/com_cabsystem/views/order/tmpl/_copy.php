<div class="modal fade add-form" data-backdrop="static" id="copyOrderModal" role="dialog" tabindex="-1" aria-labelledby="copyOrderModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="copyOrderModalLabel">Bestellung erstellen | <strong><span class="copyForm-pricedisplay"></span></strong></h4>
			</div>
			<div class="modal-body">
				<form id="copyOrderForm" class="form-horizontal" role="form">
                <div class="panel panel-info">
					<div class="panel-heading">Fahrtstrecke</div>
					<div class="panel-body">
						<?php
                        $all_districts_array = array();
                        $district_array = array();
                        $street_array = array();

                        /*$district_array['other'] = array();
                        array_push($district_array['other'],array('id'=>'other','tag'=>'Anderer'));*/
                        $street_array['other'] = array();
                        array_push($street_array['other'],array('id'=>'other','tag'=>'Andere'));

                        foreach($this->cities as $city) 
                        {
                            $district_array[$city->city_id] = array();
                            //array_push($district_array[$city->city_id],array('id'=>'other','tag'=>'Anderer'));

                            foreach($city->districts as $district)
                            {
                                array_push($district_array[$city->city_id],array('id'=>$district->district_id,'tag'=>$district->zip.' '.$district->district));
                                array_push($all_districts_array,array('id'=>$district->district_id,'tag'=>$district->zip.' '.$district->district));

                                $street_array[$district->district_id] = array();
                                array_push($street_array[$district->district_id],array('id'=>'other','tag'=>'Andere'));

                                $street_array[$district->district_id] = array();
                                foreach($district->streets as $street) 
                                {
                                    array_push($street_array[$district->district_id],array('id'=>$street->street_id,'tag'=>$street->name));
                                }
                            }
                        }
                        echo '<input id="copyForm-district-array" type="hidden" value="'.htmlentities(json_encode($district_array)).'"/>';
                        echo '<input id="copyForm-street-array" type="hidden" value="'.htmlentities(json_encode($street_array)).'"/>';
                        echo '<input id="copyForm-all-districts-array" type="hidden" value="'.htmlentities(json_encode($all_districts_array)).'"/>';
                        ?>
                        <?php
                        $all_flightnumbers_array = array();
                        $flightnumber_array = array();

                        $flightnumber_array['other'] = array();
                        array_push($flightnumber_array['other'],array('id'=>'other','tag'=>'Andere'));

                        foreach($this->destination_cities as $destination_city)
                        {
                            $flightnumber_array[$destination_city->city_id] = array();
                            array_push($flightnumber_array[$destination_city->city_id],array('id'=>'other','tag'=>'Andere'));

                            foreach($destination_city->flightnumbers as $flightnumber)
                            {
                                array_push($flightnumber_array[$destination_city->city_id],array('id'=>$flightnumber->flight_id,'tag'=>$flightnumber->flightnumber));
                                array_push($all_flightnumbers_array,array('id'=>$flightnumber->flight_id,'tag'=>$flightnumber->flightnumber));
                            }
                        }
                        echo '<input id="copyForm-flightnumber-array" type="hidden" value="'.htmlentities(json_encode($flightnumber_array)).'"/>';
                        echo '<input id="copyForm-all-flightnumbers-array" type="hidden" value="'.htmlentities(json_encode($all_flightnumbers_array)).'"/>';
                        ?>
                    
                        <div class="form-group">
                            <label for="copyForm-from_ordertype_id" class="col-md-3 control-label">Von</label>
                            <div class="col-md-9">
                                <select class="form-control" name="from_ordertype_id" id="copyForm-from_ordertype_id">
                                  <?php
                                    echo '<option value=""></option>';
                                    foreach($this->ordertypes as $ordertype) {
                                        $selected = "";
                                        if($this->order->from_ordertype_id == $ordertype->ordertype_id)
                                        {
                                            $selected = 'selected="selected"';
                                        }
                                        echo '<option value="'.$ordertype->ordertype_id.'" data-type="'.$ordertype->type.'" '.$selected.'>'.JText::_($ordertype->language_string).'</option>';	
                                    }
                                  ?>
                                </select>
                            </div>
                        </div>
                                
                        <div id="copyForm-from_ordertype_section_address" class="ordertype_section_from well">
                            <div class="form-group">
                                <label for="copyForm-from_city_id" class="col-md-3 control-label">Ort</label>
                                <div class="col-md-9">
                                    <select class="form-control" name="from_city_id" id="copyForm-from_city_id">
                                      <?php
                                        //echo '<option value="other">Anderer</option>';
                                        foreach($this->cities as $city) {
                                            $selected = "";
                                            if($this->order->from_city_id == $city->city_id)
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
                                <label for="copyForm-from_district_id" class="col-md-3 control-label">Bezirk</label>
                                <div class="col-md-9">
                                    <input type='hidden' class="form-control" name="from_district_id" id="copyForm-from_district_id" value="<?php echo $this->order->from_district_id; ?>"/>
                                </div>
                            </div>
            
                            <div class="form-group">
                                <label for="copyForm-from_street_id" class="col-md-3 control-label">Straße</label>
                                <div class="col-md-9">
                                    <input type='hidden' class="form-control" name="from_street_id" id="copyForm-from_street_id" value="<?php echo $this->order->from_street_id; ?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="copyForm-from_house" class="col-md-3 control-label">Zusatz</label>
                                <div class="col-md-9 row">
                                    <div class="col-sm-4">
                                        <input type='text' class="form-control" name="from_house" id="copyForm-from_house" placeholder="Hausnummer" value="<?php echo $this->order->from_house; ?>"/>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type='text' class="form-control" name="from_stair" id="copyForm-from_stair" placeholder="Stiege" value="<?php echo $this->order->from_stair; ?>"/>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type='text' class="form-control" name="from_door" id="copyForm-from_door" placeholder="Tür" value="<?php echo $this->order->from_door; ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                                
                        <div id="copyForm-from_ordertype_section_airport" class="ordertype_section_from well">
                            <div class="form-group">
                                <label for="copyForm-from_flight_id" class="col-md-3 control-label">Aus</label>
                                <div class="col-md-9">
                                    <select class="form-control" name="from_flight_id" id="copyForm-from_flight_id">
                                      <?php
                                        echo '<option value="other">Andere</option>';
                                        foreach($this->destination_cities as $destination_city) {
                                            $selected = "";
                                            if($this->order->flight_id == $destination_city->city_id)
                                            {
                                                $selected = 'selected="selected"';
                                            }
                                            echo '<option value="'.$destination_city->city_id.'" '.$selected.'>'.$destination_city->name.'</option>';	
                                        }
                                      ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="copyForm-flight_number" class="col-md-3 control-label">Flugnr.</label>
                                <div class="col-md-9">
                                    <input type='hidden' class="form-control" name="flight_number" id="copyForm-flight_number" value="<?php echo (!empty($this->order->flight_number)) ? $this->order->flight_number_id : '';?>"/>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="copyForm-flight_time" class="col-md-3 control-label">Ankunft</label>
                                <div class="col-md-9">
                                    <div class='input-group date' id='copyForm-flight_time-picker' data-date-format="HH:mm:ss">
                                        <input type='text' class="form-control" name="flight_time" id="copyForm-flight_time" readonly="readonly" value="<?php echo (!empty($this->order->flight_time)) ? date("H:i:s", strtotime($this->order->flight_time)) : ''; ?>"/>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                                
                        <div class="form-group">
                            <label for="copyForm-to_ordertype_id" class="col-md-3 control-label">Nach</label>
                            <div class="col-md-9">
                                <select class="form-control" name="to_ordertype_id" id="copyForm-to_ordertype_id">
                                  <?php
                                    echo '<option value=""></option>';
                                    foreach($this->ordertypes as $ordertype) {
                                        $selected = "";
                                        if($this->order->to_ordertype_id == $ordertype->ordertype_id)
                                        {
                                            $selected = 'selected="selected"';
                                        }
                                        
                                        echo '<option value="'.$ordertype->ordertype_id.'" data-type="'.$ordertype->type.'" '.$selected.'>'.JText::_($ordertype->language_string).'</option>';	
                                    }
                                  ?>
                                </select>
                            </div>
                        </div>
                                
                        <div id="copyForm-to_ordertype_section_address" class="ordertype_section_to well">
                            <div class="form-group">
                                <label for="copyForm-to_city_id" class="col-md-3 control-label">Ort</label>
                                <div class="col-md-9">
                                    <select class="form-control" name="to_city_id" id="copyForm-to_city_id">
                                      <?php
                                        echo '<option value="other">Anderer</option>';
                                        foreach($this->cities as $city) {
                                            $selected = "";
                                            if($this->order->to_city_id == $city->city_id)
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
                                <label for="copyForm-to_district_id" class="col-md-3 control-label">Bezirk</label>
                                <div class="col-md-9">
                                    <input type='hidden' class="form-control" name="to_district_id" id="copyForm-to_district_id" value="<?php echo $this->order->to_district_id; ?>"/>
                                </div>
                            </div>
            
                            <div class="form-group">
                                <label for="copyForm-to_street_id" class="col-md-3 control-label">Straße</label>
                                <div class="col-md-9">
                                    <input type='hidden' class="form-control" name="to_street_id" id="copyForm-to_street_id" value="<?php echo $this->order->to_street_id; ?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="copyForm-to_house" class="col-md-3 control-label">Zusatz</label>
                                <div class="col-md-9 row">
                                    <div class="col-sm-4">
                                        <input type='text' class="form-control" name="to_house" id="copyForm-to_house" placeholder="Hausnummer" value="<?php echo $this->order->to_house; ?>"/>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type='text' class="form-control" name="to_stair" id="copyForm-to_stair" placeholder="Stiege" value="<?php echo $this->order->to_stair; ?>"/>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type='text' class="form-control" name="to_door" id="copyForm-to_door" placeholder="Tür" value="<?php echo $this->order->to_door; ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                                
                        <!--<div id="copyForm-to_ordertype_section_airport" class="ordertype_section_to well">
                            <div class="form-group">
                                <label for="copyForm-to_flight_id" class="col-md-3 control-label">Flug</label>
                                <div class="col-md-9">
                                    <select class="form-control" name="to_flight_id" id="copyForm-to_flight_id">
                                      <?php
                                        foreach($this->flights as $flight) {
                                            $selected = "";
                                            if($this->order->flight_id == $flight->flight_id)
                                            {
                                                $selected = 'selected="selected"';
                                            }
                                            echo '<option value="'.$flight->flight_id.'" '.$selected.'>'.$flight->flightnumber.' '.$flight->city_name.':'.$flight->time.'</option>';	
                                        }
                                      ?>
                                    </select>
                                </div>
                            </div>
                        </div>-->
                                
                        <div class="form-group">
                            <label for="copyForm-date" class="col-md-3 control-label">Datum</label>
                            <div class="col-md-9">
                                <div class='input-group date' id='copyForm-date-picker' data-date-format="YYYY-MM-DD" data-min-date="<?php echo (JFactory::getUser()->authorise('cabsystem.admin', 'com_cabsystem')) ? 'false' : 'true';?>">
                                    <input type='text' class="form-control" name="date" id="copyForm-date" readonly="readonly" value="<?php echo date("Y-m-d", strtotime($this->order->datetime)); ?>"/>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="copyForm-time" class="col-md-3 control-label">Zeit</label>
                            <div class="col-md-9">
                                <div class='input-group date' id='copyForm-time-picker' data-date-format="HH:mm:ss">
                                    <input type='text' class="form-control" name="time" id="copyForm-time" readonly="readonly" value="<?php echo date("H:i:s", strtotime($this->order->datetime)); ?>" data-check="true"/>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                  </div>
                </div>
                
                <div class="panel panel-info">
					<div class="panel-heading">Zur Person</div>
					<div class="panel-body">
                        <div class="form-group">
                            <label for="copyForm-salutation_id" class="col-md-3 control-label">Anrede</label>
                            <div class="col-md-9">
                                <select class="form-control" name="salutation_id" id="copyForm-salutation_id">
                                  <?php
                                    foreach($this->salutations as $salutation) {
                                        $selected = "";
                                        if($this->order->salutation_id == $salutation->salutation_id) 
                                        {
                                            $selected = 'selected="selected"';
                                        }
                                        
                                        echo '<option value="'.$salutation->salutation_id.'" '.$selected.'>'.$salutation->name.'</option>';	
                                    }
                                  ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="copyForm-title_id" class="col-md-3 control-label">Titel</label>
                            <div class="col-md-9">
                                <select class="form-control" name="title_id" id="copyForm-title_id">
                                  <?php
                                    echo '<option value=""></option>';
                                    echo '<option value="other">Anderer</option>';
                                    foreach($this->titles as $title) {
                                        $selected = "";
                                        if($this->order->title_id == $title->title_id) 
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
                            <label for="copyForm-name" class="col-md-3 control-label">Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="copyForm-name" name="name" value="<?php echo $this->order->name; ?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="copyForm-phone" class="col-md-3 control-label">Telefon</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="copyForm-phone" name="phone" value="<?php echo $this->order->phone; ?>">
                            </div>
                
                        </div>
                
                        <div class="form-group">
                            <label for="copyForm-email" class="col-md-3 control-label">Email</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="copyForm-email" name="email" value="<?php echo $this->order->email; ?>">
                            </div>
                        </div>
                        
                        <?php
                        //Wenn Admin dann soll die Checkbox bleiben
                        if (JFactory::getUser()->authorise('cabsystem.admin', 'com_cabsystem')) {
                        ?>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-md-9">
                                <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="send_email"> Bestätigungs-Email senden
                                </label>
                                </div>
                            </div>
                        </div>
                        <?php
                        }
                        //Wenn KEIN Admin dann soll die Bestaetigung IMMER geschickt werden
                        else {
                            echo '<input type="hidden" name="send_email" value="1"/>';
                        }
                        ?>
                    </div>
				</div>
                    
                <div class="panel panel-info">
					<div class="panel-heading">Infos zur Fahrt</div>
					<div class="panel-body">
                        <div class="form-group">
                            <label for="copyForm-cartype_id" class="col-md-3 control-label">Autotyp</label>
                            <div class="col-md-9">
                                <select class="form-control" name="cartype_id" id="copyForm-cartype_id">
                                  <?php
                                    foreach($this->cartypes as $cartype) {
                                        $selected = "";
                                        if($this->order->cartype_id == $cartype->cartype_id) 
                                        {
                                            $selected = 'selected="selected"';
                                        }                        
                                        echo '<option value="'.$cartype->cartype_id.'" '.$selected.' data-persons="'.$cartype->persons.'" data-luggage="'.$cartype->luggage.'" data-handluggage="'.$cartype->handluggage.'">'.$cartype->name.'</option>';	
                                    }
                                  ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="copyForm-persons" class="col-md-3 control-label">Personen</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control show-spinner" id="copyForm-persons" name="persons" value="<?php echo $this->order->persons; ?>"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="copyForm-luggage" class="col-md-3 control-label">Koffer</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control show-spinner" id="copyForm-luggage" name="luggage" value="<?php echo $this->order->luggage; ?>"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="copyForm-handluggage" class="col-md-3 control-label">Handgepäck</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control show-spinner" id="copyForm-handluggage" name="handluggage" value="<?php echo $this->order->handluggage; ?>"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="copyForm-child_seat" class="col-md-3 control-label">Kindersitz</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control show-spinner" id="copyForm-child_seat" name="child_seat" value="<?php echo (!empty($this->order->child_seat)) ? $this->order->child_seat : ''?>"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="copyForm-maxi_cosi" class="col-md-3 control-label">Maxi Cosi</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control show-spinner" id="copyForm-maxi_cosi" name="maxi_cosi" value="<?php echo (!empty($this->order->maxi_cosi)) ? $this->order->maxi_cosi : ''?>"/>
                                <p class="form-control-static">Die Anzahl an Kindersitzen und Maxi Cosis darf 3 nicht übersteigen</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="copyForm-child_seat_elevation" class="col-md-3 control-label">Kindersitzerhöh.</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control show-spinner" id="copyForm-child_seat_elevation" name="child_seat_elevation" data-max="3" value="<?php echo (!empty($this->order->child_seat_elevation)) ? $this->order->child_seat_elevation : ''?>"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="copyForm-additionaladdresses_id" class="col-md-3 control-label">Zstz.Adr.</label>
                            <div class="col-md-9">
                                <select class="form-control" name="additionaladdresses_id" id="copyForm-additionaladdresses_id">
                                  <?php
                                    echo '<option value=""></option>';
                                    foreach($this->additionaladdresses as $additionaladdress) {
                                        $selected = "";
                                        if($this->order->additionaladdresses_id == $additionaladdress->additionaladdress_id) 
                                        {
                                            $selected = 'selected="selected"';
                                        }
                                        echo '<option data-districts="'.$additionaladdress->districts.'" value="'.$additionaladdress->additionaladdress_id.'" '.$selected.'>'.$additionaladdress->name.'</option>';	
                                    }
                                  ?>
                                </select>
                            </div>
                        </div>
                        <div id="copyForm-additionaladdresses_districts">
                            <?php
                                $this->order->additional_address_districts = json_decode($this->order->additional_address_districts);
                                for($i=1;$i<=$this->order->additional_address_districts_amount;$i++) {
									if(is_array($this->order->additional_address_districts[($i-1)])) {
										echo '<div class="form-group">';
										echo '<label for="additionaladdresses_district_'.$i.'" class="col-md-3 control-label">Ziel '.$i.'</label>';
										echo '<div class="col-md-9">';
											echo '<input type="hidden" class="form-control additionaladdresses_district" name="additionaladdress_districts[]" id="copyForm-additionaladdresses_district_'.$i.'" data-select-value="'.$this->order->additional_address_districts[($i-1)][0].'"/>';
										echo '</div>';
										echo '</div>';
										echo '<div class="form-group">';
										echo '<label for="additionaladdresses_district_address_'.$i.'" class="col-md-3 control-label">Adresse '.$i.'</label><div class="col-md-9"><input type="text" class="form-control additionaladdresses_district_address" name="additionaladdress_districts_addresses[]" id="copyForm-additionaladdresses_district_address_'.$i.'" value="'.$this->order->additional_address_districts[($i-1)][1].'"/>';
										echo '</div>';
										echo '</div>';
									}
									else {
										echo '<div class="form-group">';
										echo '<label for="additionaladdresses_district_'.$i.'" class="col-md-3 control-label">Ziel '.$i.'</label>';
										echo '<div class="col-md-9">';
											echo '<input type="hidden" class="form-control additionaladdresses_district" name="additionaladdress_districts[]" id="copyForm-additionaladdresses_district_'.$i.'" data-select-value="'.$this->order->additional_address_districts[($i-1)].'"/>';
										echo '</div>';
										echo '</div>';
										echo '<div class="form-group">';
										echo '<label for="additionaladdresses_district_address_'.$i.'" class="col-md-3 control-label">Adresse '.$i.'</label><div class="col-md-9"><input type="text" class="form-control additionaladdresses_district_address" name="additionaladdress_districts_addresses[]" id="copyForm-additionaladdresses_district_address_'.$i.'" value=""/>';
										echo '</div>';
										echo '</div>';
									}
                                }
                            ?>
                        </div>
                        <div class="form-group">
                            <label for="copyForm-paymentmethod_id" class="col-md-3 control-label">Bezahl.</label>
                            <div class="col-md-9">
                                <select class="form-control" name="paymentmethod_id" id="copyForm-paymentmethod_id">
                                  <?php
                                    foreach($this->paymentmethods as $paymentmethod) {
                                        $selected = "";
                                        if($this->order->paymentmethod_id == $paymentmethod->paymentmethod_id) 
                                        {
                                            $selected = 'selected="selected"';
                                        }       
                                        /*echo '<option value="'.$paymentmethod->paymentmethod_id.'" '.$selected.'>'.$paymentmethod->name.' (+ € '.number_format($paymentmethod->price,2).')'.'</option>';	*/
                                        echo '<option value="'.$paymentmethod->paymentmethod_id.'" '.$selected.'>'.$paymentmethod->name.'</option>';	
                                    }
                                  ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="copyForm-comment" class="col-md-3 control-label">Anm.</label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="comment" id="copyForm-comment" rows="3"><?php echo $this->order->comment; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="copyForm-postorder" class="col-md-3 control-label">Rückfahrt</label>
                            <div class="col-md-9">
                                <select class="form-control" name="postorder" id="copyForm-postorder">
                                    <?php
                                    echo '<option value="0" selected="selected">Nein, keine Rückfahrt buchen</option>';
                                    echo '<option value="1">Ja, Rückfahrt buchen</option>';
                                    ?>
                                </select>
                            </div>
                        </div>
                        <?php
                        //Wenn Admin dann soll Preis-Override Feld angezeigt werden
                        if (JFactory::getUser()->authorise('cabsystem.admin', 'com_cabsystem')) {
                        ?>
                        <div class="form-group">
                            <label for="copyForm-price_override" class="col-md-3 control-label">Individualpreis</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="copyForm-price_override" name="price_override" value="<?php echo ($this->order->price_override) ? $this->order->price : '';?>"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-9 col-sm-offset-3"><li class="fa fa-warning"></li> Überschreibt den berechneten Preis (auch für Rückfahrt)</div>
                        </div>
                        <?php
                        }
                        ?>
                	</div>
                </div>
                <div id="copyForm-postorder_wrapper" class="panel panel-info">
                    <div class="panel-heading">Rückfahrt</div>
                    <div class="panel-body">
                        <p>Wenn Sie eine Rückfahrt mit den umgekehrten Einstellungen buchen möchten, geben Sie bitte folgende Informationen an. Der Preis der Rückfahrt entspricht dabei dem Preis dieser Fahrt.</p>
                        <div class="form-group">
                            <label for="copyForm-postorder_date" class="col-md-3 control-label">Datum</label>
                            <div class="col-md-9">
                                <div class='input-group date' id='copyForm-postorder_date-picker' data-date-format="YYYY-MM-DD" data-min-date="<?php echo (JFactory::getUser()->authorise('cabsystem.admin', 'com_cabsystem')) ? 'false' : 'true';?>">
                                    <input type='text' class="form-control" name="postorder_date" id="copyForm-postorder_date" readonly="readonly"/>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="copyForm-postorder_time" class="col-md-3 control-label">Zeit</label>
                            <div class="col-md-9">
                                <div class='input-group date' id='copyForm-postorder_time-picker' data-date-format="HH:mm:ss">
                                    <input type='text' class="form-control" name="postorder_time" id="copyForm-postorder_time" readonly="readonly" data-check="true"/>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span>
                                </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-offset-3 col-md-9">
                            <p class="form-static-control">Nur bei Rückfahrten vom Flughafen</p>
                        </div>
                        <div class="form-group">
                            <label for="copyForm-postorder_from_flight_id" class="col-md-3 control-label">Aus</label>
                            <div class="col-md-9">
                                <select class="form-control" name="postorder_from_flight_id" id="copyForm-postorder_from_flight_id">
                                    <?php
                                    echo '<option value="other">Andere</option>';
                                    foreach($this->destination_cities as $destination_city) {
                                        echo '<option value="'.$destination_city->city_id.'">'.$destination_city->name.'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="copyForm-postorder_flight_number" class="col-md-3 control-label">Flugnr.</label>
                            <div class="col-md-9">
                                <input type='hidden' class="form-control" name="postorder_flight_number" id="copyForm-postorder_flight_number" />
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="created" id="copyForm-created" value="<?php echo $this->order->created; ?>" />
                <input type="hidden" name="price" id="copyForm-price" value="<?php echo $this->order->price; ?>" />
                
                <input type="hidden" name="view" value="order" />
                <input type="hidden" name="model" value="Order" />
                <input type="hidden" name="item" value="order" />
                <input type="hidden" name="table" value="orders" />
			</div>
			<div class="modal-footer">
            	<p>Der Preis Ihrer Bestellung ist <strong><span class="copyForm-pricedisplay"><?php echo $this->order->price; ?></span></strong></p>
				<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
				<button type="button" class="btn btn-primary" id="copyOrder">Speichern</button>
				</form>
			</div>
		</div>
	</div>
</div>