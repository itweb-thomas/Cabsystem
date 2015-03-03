<div class="modal fade add-form" data-backdrop="static" id="addOrderModal" role="dialog" tabindex="-1" aria-labelledby="addOrderModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="addOrderModalLabel">Bestellung erstellen | <strong><span class="addForm-pricedisplay"></span></strong></h4>
			</div>
			<div class="modal-body">
				<form id="addOrderForm" class="form-horizontal" role="form">
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
                            foreach($district->streets as $street) 
                            {
                                array_push($street_array[$district->district_id],array('id'=>$street->street_id,'tag'=>$street->name));
                            }
                        }
                    }
                    //Wenn KEIN Admin, dann nur Wiener Bezirke
                    if (!JFactory::getUser()->authorise('cabsystem.admin', 'com_cabsystem')) {
                        //Nur Wiener Bezirke = 121
                        $all_districts_array = $district_array[121];
                    }
                    echo '<input id="addForm-district-array" type="hidden" value="'.htmlentities(json_encode($district_array)).'"/>';
                    echo '<input id="addForm-street-array" type="hidden" value="'.htmlentities(json_encode($street_array)).'"/>';
                    echo '<input id="addForm-all-districts-array" type="hidden" value="'.htmlentities(json_encode($all_districts_array)).'"/>';
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
                    echo '<input id="addForm-flightnumber-array" type="hidden" value="'.htmlentities(json_encode($flightnumber_array)).'"/>';
                    echo '<input id="addForm-all-flightnumbers-array" type="hidden" value="'.htmlentities(json_encode($all_flightnumbers_array)).'"/>';
                    ?>
                    <?php
                    $lockouts_array = array();
                    foreach($this->lockouts as $lockout)
                    {
                        if($lockout->active == 1) {
                            array_push($lockouts_array,array('date'=>date("Y-m-d", strtotime($lockout->date)),'hour'=>$lockout->hour));
                        }
                    }
                    echo '<input id="addForm-lockouts-array" type="hidden" value="'.htmlentities(json_encode($lockouts_array)).'"/>';
                    ?>
                
                    <div class="form-group">
                        <label for="addForm-from_ordertype_id" class="col-md-3 control-label">Von</label>
                        <div class="col-md-9">
                            <select class="form-control" name="from_ordertype_id" id="addForm-from_ordertype_id">
                              <?php
                                echo '<option value=""></option>';
                                foreach($this->ordertypes as $ordertype) {
                                    echo '<option value="'.$ordertype->ordertype_id.'" data-type="'.$ordertype->type.'">'.JText::_($ordertype->language_string).'</option>';	
                                }
                              ?>
                            </select>
                        </div>
                    </div>
                            
                    <div id="addForm-from_ordertype_section_address" class="ordertype_section_from well">
                        <div class="form-group">
                            <label for="addForm-from_city_id" class="col-md-3 control-label">Ort</label>
                            <div class="col-md-9">
                                <select class="form-control" name="from_city_id" id="addForm-from_city_id">
                                  <?php
                                    //echo '<option value="other">Anderer</option>';
                                    foreach($this->cities as $city) {
                                        echo '<option value="'.$city->city_id.'">'.$city->name.'</option>';	
                                    }
                                  ?>
                                </select>
                            </div>
                        </div>
        
                        <div class="form-group">
                            <label for="addForm-from_district_id" class="col-md-3 control-label">Bezirk</label>
                            <div class="col-md-9">
                                <input type='hidden' class="form-control" name="from_district_id" id="addForm-from_district_id" />
                            </div>
                        </div>
        
                        <div class="form-group">
                            <label for="addForm-from_street_id" class="col-md-3 control-label">Straße</label>
                            <div class="col-md-9">
                                <input type='hidden' class="form-control" name="from_street_id" id="addForm-from_street_id" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="addForm-from_house" class="col-md-3 control-label">Zusatz</label>
                            <div class="col-md-9 row">
                                <div class="col-sm-4">
                                    <input type='text' class="form-control" name="from_house" id="addForm-from_house" placeholder="Hausnummer"/>
                                </div>
                                <div class="col-sm-4">
                                    <input type='text' class="form-control" name="from_stair" id="addForm-from_stair" placeholder="Stiege"/>
                                </div>
                                <div class="col-sm-4">
                                    <input type='text' class="form-control" name="from_door" id="addForm-from_door" placeholder="Tür"/>
                                </div>
                            </div>
                        </div>
                    </div>
                            
                    <div id="addForm-from_ordertype_section_airport" class="ordertype_section_from well">
                        <div class="form-group">
        
                            <label for="addForm-from_flight_id" class="col-md-3 control-label">Aus</label>
                            <div class="col-md-9">
                                <select class="form-control" name="from_flight_id" id="addForm-from_flight_id">
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
                            <label for="addForm-flight_number" class="col-md-3 control-label">Flugnr.</label>
                            <div class="col-md-9">
                                <input type='hidden' class="form-control" name="flight_number" id="addForm-flight_number" />
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="addForm-flight_time" class="col-md-3 control-label">Ankunft</label>
                            <div class="col-md-9">
                                <div class='input-group date' id='addForm-flight_time-picker' data-date-format="HH:mm:ss">
                                    <input type='text' class="form-control" name="flight_time" id="addForm-flight_time" readonly="readonly"/>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                            
                    <div class="form-group">
                        <label for="addForm-to_ordertype_id" class="col-md-3 control-label">Nach</label>
                        <div class="col-md-9">
                            <select class="form-control" name="to_ordertype_id" id="addForm-to_ordertype_id">
                              <?php
                                echo '<option value=""></option>';
                                foreach($this->ordertypes as $ordertype) {
                                    echo '<option value="'.$ordertype->ordertype_id.'" data-type="'.$ordertype->type.'">'.JText::_($ordertype->language_string).'</option>';	
                                }
                              ?>
                            </select>
                        </div>
                    </div>
                            
                    <div id="addForm-to_ordertype_section_address" class="ordertype_section_to well">
                        <div class="form-group">
                            <label for="addForm-to_city_id" class="col-md-3 control-label">Ort</label>
                            <div class="col-md-9">
                                <select class="form-control" name="to_city_id" id="addForm-to_city_id">
                                  <?php
                                    //echo '<option value="other">Anderer</option>';
                                    foreach($this->cities as $city) {
                                        echo '<option value="'.$city->city_id.'">'.$city->name.'</option>';	
                                    }
                                  ?>
                                </select>
                            </div>
                        </div>
        
                        <div class="form-group">
                            <label for="addForm-to_district_id" class="col-md-3 control-label">Bezirk</label>
                            <div class="col-md-9">
                                <input type='hidden' class="form-control" name="to_district_id" id="addForm-to_district_id" />
                            </div>
                        </div>
        
                        <div class="form-group">
                            <label for="addForm-to_street_id" class="col-md-3 control-label">Straße</label>
                            <div class="col-md-9">
                                <input type='hidden' class="form-control" name="to_street_id" id="addForm-to_street_id" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="addForm-to_house" class="col-md-3 control-label">Zusatz</label>
                            <div class="col-md-9 row">
                                <div class="col-sm-4">
                                    <input type='text' class="form-control" name="to_house" id="addForm-to_house" placeholder="Hausnummer"/>
                                </div>
                                <div class="col-sm-4">
                                    <input type='text' class="form-control" name="to_stair" id="addForm-to_stair" placeholder="Stiege"/>
                                </div>
                                <div class="col-sm-4">
                                    <input type='text' class="form-control" name="to_door" id="addForm-to_door" placeholder="Tür"/>
                                </div>
                            </div>
                        </div>
                    </div>
                            
                    <!--<div id="addForm-to_ordertype_section_airport" class="ordertype_section_to well">
                        <div class="form-group">
                            <label for="addForm-to_flight_id" class="col-md-3 control-label">Aus</label>
                            <div class="col-md-9">
                                <select class="form-control" name="to_flight_id" id="addForm-to_flight_id">
                                  <?php
                                    foreach($this->destination_cities as $destination_city) {
                                        echo '<option value="'.$destination_city->city_id.'">'.$destination_city->name.'</option>';	
                                    }
                                  ?>
                                </select>
                            </div>
                        </div>
                    </div>-->
                            
                    <div class="form-group">
                        <label for="addForm-date" class="col-md-3 control-label">Datum</label>
                        <div class="col-md-9">
                            <div class='input-group date' id='addForm-date-picker' data-date-format="YYYY-MM-DD" data-min-date="<?php echo (JFactory::getUser()->authorise('cabsystem.admin', 'com_cabsystem')) ? 'false' : 'true';?>">
                                <input type='text' class="form-control" name="date" id="addForm-date" readonly="readonly"/>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="addForm-time" class="col-md-3 control-label">Zeit</label>
                        <div class="col-md-9">
                            <div class='input-group date' id='addForm-time-picker' data-date-format="HH:mm:ss">
                                <input type='text' class="form-control" name="time" id="addForm-time" readonly="readonly" data-check="true"/>
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
                            <label for="addForm-salutation_id" class="col-md-3 control-label">Anrede</label>
                            <div class="col-md-9">
                                <select class="form-control" name="salutation_id" id="addForm-salutation_id">
                                  <?php
                                    foreach($this->salutations as $salutation) {
                                        echo '<option value="'.$salutation->salutation_id.'">'.$salutation->name.'</option>';	
                                    }
                                  ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="addForm-title_id" class="col-md-3 control-label">Titel</label>
                            <div class="col-md-9">
                                <select class="form-control" name="title_id" id="addForm-title_id">
                                  <?php
                                    echo '<option value=""></option>';
                                    echo '<option value="other">Anderer</option>';
                                    foreach($this->titles as $title) {
                                        echo '<option value="'.$title->title_id.'">'.$title->name.'</option>';	
                                    }
                                  ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="addForm-name" class="col-md-3 control-label">Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="addForm-name" name="name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="addForm-phone" class="col-md-3 control-label">Telefon</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="addForm-phone" name="phone">
                            </div>
                        </div>
                        <?php
                        $check_email = 'true';
                        //Wenn Admin dann soll die Checkbox bleiben
                        if (JFactory::getUser()->authorise('cabsystem.admin', 'com_cabsystem')) {
                            $check_email = 'false';
                        }
                        ?>
                        
                        <div class="form-group">
                            <label for="addForm-email" class="col-md-3 control-label">Email</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="addForm-email" name="email" data-check="<?php echo $check_email;?>" />
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
                            <label for="addForm-cartype_id" class="col-md-3 control-label">Autotyp</label>
                            <div class="col-md-9">
                                <select class="form-control" name="cartype_id" id="addForm-cartype_id">
                                  <?php
                                    foreach($this->cartypes as $cartype) {
                                        echo '<option value="'.$cartype->cartype_id.'" data-persons="'.$cartype->persons.'" data-luggage="'.$cartype->luggage.'" data-handluggage="'.$cartype->handluggage.'">'.$cartype->name.'</option>';	
                                    }
                                  ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="addForm-persons" class="col-md-3 control-label">Personen</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control show-spinner" id="addForm-persons" name="persons"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="addForm-luggage" class="col-md-3 control-label">Koffer</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control show-spinner" id="addForm-luggage" name="luggage"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="addForm-handluggage" class="col-md-3 control-label">Handgepäck</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control show-spinner" id="addForm-handluggage" name="handluggage"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="addForm-child_seat" class="col-md-3 control-label">Kindersitz</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control show-spinner" id="addForm-child_seat" name="child_seat" data-max="3"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="addForm-maxi_cosi" class="col-md-3 control-label">Maxi Cosi</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control show-spinner" id="addForm-maxi_cosi" name="maxi_cosi" data-max="3"/>
                                <p class="form-control-static">Die Anzahl an Kindersitzen und Maxi Cosis darf 3 nicht übersteigen</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="addForm-child_seat_elevation" class="col-md-3 control-label">Kindersitzerhöh.</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control show-spinner" id="addForm-child_seat_elevation" name="child_seat_elevation" data-max="3"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="addForm-additionaladdresses_id" class="col-md-3 control-label">Zstz.Adr.</label>
                            <div class="col-md-9">
                                <select class="form-control" name="additionaladdresses_id" id="addForm-additionaladdresses_id">
                                  <?php
                                    echo '<option value=""></option>';
                                    foreach($this->additionaladdresses as $additionaladdress) {
                                        echo '<option data-districts="'.$additionaladdress->districts.'" value="'.$additionaladdress->additionaladdress_id.'">'.$additionaladdress->name.'</option>';	
                                    }
                                  ?>
                                </select>
                            </div>
                        </div>
                        <div id="addForm-additionaladdresses_districts"></div>
                        <div class="form-group">
                            <label for="addForm-paymentmethod_id" class="col-md-3 control-label">Bezahl.</label>
                            <div class="col-md-9">
                                <select class="form-control" name="paymentmethod_id" id="addForm-paymentmethod_id">
                                  <?php
                                    foreach($this->paymentmethods as $paymentmethod) {
                                        /*echo '<option value="'.$paymentmethod->paymentmethod_id.'" data-paymentmethod_price="'.$paymentmethod->price.'">'.$paymentmethod->name.' (+ € '.number_format($paymentmethod->price,2).')'.'</option>';*/	
                                        echo '<option value="'.$paymentmethod->paymentmethod_id.'" data-paymentmethod_price="'.$paymentmethod->price.'">'.$paymentmethod->name.'</option>';	
                                    }
                                  ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="addForm-comment" class="col-md-3 control-label">Anm.</label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="comment" id="addForm-comment" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="addForm-postorder" class="col-md-3 control-label">Rückfahrt</label>
                            <div class="col-md-9">
                                <select class="form-control" name="postorder" id="addForm-postorder">
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
                            <label for="addForm-price_override" class="col-md-3 control-label">Individualpreis</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="addForm-price_override" name="price_override"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-9 col-sm-offset-3"><li class="fa fa-warning"></li> Überschreibt den berechneten Preis (auch für Rückfahrt)</div>
                        </div>
                        <?php
                        }
                        //Ansonsten AGB akzeptieren
                        else {
                        ?>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-md-9">
                                <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="tos_accepted" id="addForm-tos_accepted" checked=""/> 
                                    Ich akzeptiere die <a href="/agb.html" target="_blank">AGBs</a>
                                </label>
                                </div>
                            </div>
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                    
                <div id="addForm-postorder_wrapper" class="panel panel-info">
					<div class="panel-heading">Rückfahrt</div>
					<div class="panel-body">
                    	<p>Wenn Sie eine Rückfahrt mit den umgekehrten Einstellungen buchen möchten, geben Sie bitte folgende Informationen an. Der Preis der Rückfahrt entspricht dabei dem Preis dieser Fahrt.</p>
                        <div class="form-group">
                            <label for="addForm-postorder_date" class="col-md-3 control-label">Datum</label>
                            <div class="col-md-9">
                                <div class='input-group date' id='addForm-postorder_date-picker' data-date-format="YYYY-MM-DD" data-min-date="<?php echo (JFactory::getUser()->authorise('cabsystem.admin', 'com_cabsystem')) ? 'false' : 'true';?>">
                                    <input type='text' class="form-control" name="postorder_date" id="addForm-postorder_date" readonly="readonly"/>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="addForm-postorder_time" class="col-md-3 control-label">Zeit</label>
                            <div class="col-md-9">
                                <div class='input-group date' id='addForm-postorder_time-picker' data-date-format="HH:mm:ss">
                                    <input type='text' class="form-control" name="postorder_time" id="addForm-postorder_time" readonly="readonly" data-check="true"/>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-offset-3 col-md-9">
                        	<p class="form-static-control">Nur bei Rückfahrten vom Flughafen</p>
                        </div>
                        <div class="form-group">
                            <label for="addForm-postorder_from_flight_id" class="col-md-3 control-label">Aus</label>
                            <div class="col-md-9">
                                <select class="form-control" name="postorder_from_flight_id" id="addForm-postorder_from_flight_id">
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
                            <label for="addForm-postorder_flight_number" class="col-md-3 control-label">Flugnr.</label>
                            <div class="col-md-9">
                                <input type='hidden' class="form-control" name="postorder_flight_number" id="addForm-postorder_flight_number" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-right"><small>Alle berechneten Preise ohne Gewähr</small></div>
                <input type="hidden" name="price" id="addForm-price" value="NULL" />  
                <input type="hidden" name="view" value="order" />
                <input type="hidden" name="model" value="Order" />
                <input type="hidden" name="item" value="order" />
                <input type="hidden" name="table" value="orders" />
			</div>
			<div class="modal-footer">
            	<p>Der Preis Ihrer Bestellung ist <strong><span class="addForm-pricedisplay"></span></strong></p>
				<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
				<button type="button" class="btn btn-primary" id="addOrder">Bestellung aufgeben</button>
				</form>
			</div>
		</div>
	</div>
</div>