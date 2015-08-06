<div class="modal fade add-form" data-backdrop="static" id="addOrderModal" role="dialog" tabindex="-1" aria-labelledby="addOrderModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="addOrderModalLabel"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_HEADLINE');?> | <strong><span class="addForm-pricedisplay"></span></strong></h4>
			</div>
			<div class="modal-body">
				<form id="addOrderForm" class="form-horizontal" role="form">
                <div class="panel panel-info">
					<div class="panel-heading"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_PANEL_ROUTE_HEADLINE');?></div>
					<div class="panel-body">
					<?php
                    $all_districts_array = array();
                    $district_array = array();
                    $street_array = array();

                    /*$district_array['other'] = array();
                    array_push($district_array['other'],array('id'=>'other','tag'=>'Anderer'));*/
                    $street_array['other'] = array();
                    array_push($street_array['other'],array('id'=>'other','tag'=>JText::_('COM_CABSYSTEM_OTHER_F')));

                    foreach($this->cities as $city) 
                    {
                        $district_array[$city->city_id] = array();
                        //array_push($district_array[$city->city_id],array('id'=>'other','tag'=>'Anderer'));

                        foreach($city->districts as $district) 
                        {
                            array_push($district_array[$city->city_id],array('id'=>$district->district_id,'tag'=>$district->zip.' '.$district->district));
                            array_push($all_districts_array,array('id'=>$district->district_id,'tag'=>$district->zip.' '.$district->district));
                            
                            $street_array[$district->district_id] = array();
                            array_push($street_array[$district->district_id],array('id'=>'other','tag'=>JText::_('COM_CABSYSTEM_OTHER_F')));
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
                    array_push($flightnumber_array['other'],array('id'=>'other','tag'=>JText::_('COM_CABSYSTEM_OTHER_F')));

                    foreach($this->destination_cities as $destination_city)
                    {
                        $flightnumber_array[$destination_city->city_id] = array();
                        array_push($flightnumber_array[$destination_city->city_id],array('id'=>'other','tag'=>JText::_('COM_CABSYSTEM_OTHER_F')));

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
                            array_push($lockouts_array,array('date'=>date("Y-m-d", strtotime($lockout->date)),'hour'=>$lockout->hour,'type'=>$lockout->type));
                        }
                    }
                    echo '<input id="addForm-lockouts-array" type="hidden" value="'.htmlentities(json_encode($lockouts_array)).'"/>';
                    ?>
                
                    <div class="form-group">
                        <label for="addForm-from_ordertype_id" class="col-md-3 control-label"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_FROM_ORDERTYPE_ID');?><span class="asterisk">*</span></label>
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
                            <label for="addForm-from_city_id" class="col-md-3 control-label"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_FROM_CITY_ID');?><span class="asterisk">*</span></label>
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
                            <label for="addForm-from_district_id" class="col-md-3 control-label"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_FROM_DISTRICT_ID');?><span class="asterisk">*</span></label>
                            <div class="col-md-9">
                                <input type='hidden' class="form-control" name="from_district_id" id="addForm-from_district_id" />
                            </div>
                        </div>
        
                        <div class="form-group">
                            <label for="addForm-from_street_id" class="col-md-3 control-label"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_FROM_STREET_ID');?><span class="asterisk">*</span></label>
                            <div class="col-md-9">
                                <input type='hidden' class="form-control" name="from_street_id" id="addForm-from_street_id" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="addForm-from_house" class="col-md-3 control-label"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_FROM_ADDITIONAL');?></label>
                            <div class="col-md-9 row">
                                <div class="col-sm-4">
                                    <input type='text' class="form-control" name="from_house" id="addForm-from_house" placeholder="<?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_FROM_HOUSE');?>"/>
                                </div>
                                <div class="col-sm-4">
                                    <input type='text' class="form-control" name="from_stair" id="addForm-from_stair" placeholder="<?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_FROM_STAIR');?>"/>
                                </div>
                                <div class="col-sm-4">
                                    <input type='text' class="form-control" name="from_door" id="addForm-from_door" placeholder="<?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_FROM_DOOR');?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                            
                    <div id="addForm-from_ordertype_section_airport" class="ordertype_section_from well">
                        <div class="form-group">
                            <label for="addForm-from_flight_id" class="col-md-3 control-label"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_FROM_FLIGHT_ID');?><span class="asterisk">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control" name="from_flight_id" id="addForm-from_flight_id">
                                  <?php
                                    echo '<option value="other">'.JText::_('COM_CABSYSTEM_OTHER_F').'</option>';
                                    foreach($this->destination_cities as $destination_city) {
                                        echo '<option value="'.$destination_city->city_id.'">'.$destination_city->name.'</option>';	
                                    }
                                  ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="addForm-flight_number" class="col-md-3 control-label"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_FROM_FLIGHT_NUMBER');?></label>
                            <div class="col-md-9">
                                <input type='hidden' class="form-control" name="flight_number" id="addForm-flight_number" />
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="addForm-flight_time" class="col-md-3 control-label"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_FLIGHT_TIME');?></label>
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
                        <label for="addForm-to_ordertype_id" class="col-md-3 control-label"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_TO_ORDERTYPE_ID');?><span class="asterisk">*</span></label>
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
                            <label for="addForm-to_city_id" class="col-md-3 control-label"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_TO_CITY_ID');?><span class="asterisk">*</span></label>
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
                            <label for="addForm-to_district_id" class="col-md-3 control-label"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_TO_DISTRICT_ID');?><span class="asterisk">*</span></label>
                            <div class="col-md-9">
                                <input type='hidden' class="form-control" name="to_district_id" id="addForm-to_district_id" />
                            </div>
                        </div>
        
                        <div class="form-group">
                            <label for="addForm-to_street_id" class="col-md-3 control-label"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_TO_STREET_ID');?><span class="asterisk">*</span></label>
                            <div class="col-md-9">
                                <input type='hidden' class="form-control" name="to_street_id" id="addForm-to_street_id" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="addForm-to_house" class="col-md-3 control-label"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_TO_ADDITIONAL');?></label>
                            <div class="col-md-9 row">
                                <div class="col-sm-4">
                                    <input type='text' class="form-control" name="to_house" id="addForm-to_house" placeholder="<?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_TO_HOUSE');?>"/>
                                </div>
                                <div class="col-sm-4">
                                    <input type='text' class="form-control" name="to_stair" id="addForm-to_stair" placeholder="<?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_TO_STAIR');?>"/>
                                </div>
                                <div class="col-sm-4">
                                    <input type='text' class="form-control" name="to_door" id="addForm-to_door" placeholder="<?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_TO_DOOR');?>"/>
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
                        <label for="addForm-date" class="col-md-3 control-label"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_DATE');?><span class="asterisk">*</span></label>
                        <div class="col-md-9">
                            <div class='input-group date' id='addForm-date-picker' data-date-format="YYYY-MM-DD" data-min-date="<?php echo (JFactory::getUser()->authorise('cabsystem.admin', 'com_cabsystem')) ? 'false' : 'true';?>">
                                <input type='text' class="form-control" name="date" id="addForm-date" readonly="readonly"/>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="addForm-time" class="col-md-3 control-label"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_TIME');?><span class="asterisk">*</span></label>
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
					<div class="panel-heading"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_PANEL_CUSTOMER_HEADLINE');?></div>
					<div class="panel-body">
                        <div class="form-group">
                            <label for="addForm-salutation_id" class="col-md-3 control-label"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_SALUTATION_ID');?></label>
                            <div class="col-md-9">
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
                            <label for="addForm-title_id" class="col-md-3 control-label"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_TITLE');?></label>
                            <div class="col-md-9">
                                <select class="form-control" name="title_id" id="addForm-title_id">
                                  <?php
                                    echo '<option value=""></option>';
                                    echo '<option value="other">'.JText::_('COM_CABSYSTEM_OTHER_M').'</option>';
                                    foreach($this->titles as $title) {
                                        echo '<option value="'.$title->title_id.'">'.$title->name.'</option>';	
                                    }
                                  ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="addForm-name" class="col-md-3 control-label"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_NAME');?><span class="asterisk">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="addForm-name" name="name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="addForm-phone" class="col-md-3 control-label"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_PHONE');?><span class="asterisk">*</span></label>
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
                            <label for="addForm-email" class="col-md-3 control-label"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_EMAIL');?><span class="asterisk">*</span></label>
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
                                    <input type="checkbox" name="send_email"> <?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_SEND_EMAIL');?>
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
					<div class="panel-heading"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_PANEL_INFOS_HEADLINE');?></div>
					<div class="panel-body">
                        <div class="form-group">
                            <label for="addForm-cartype_id" class="col-md-3 control-label"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_CARTYPE_ID');?><span class="asterisk">*</span></label>
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
                            <label for="addForm-persons" class="col-md-3 control-label"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_PERSONS');?></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control show-spinner" id="addForm-persons" name="persons"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="addForm-luggage" class="col-md-3 control-label"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_LUGGAGE');?></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control show-spinner" id="addForm-luggage" name="luggage"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="addForm-handluggage" class="col-md-3 control-label"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_HANDLUGGAGE');?></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control show-spinner" id="addForm-handluggage" name="handluggage"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="addForm-child_seat" class="col-md-3 control-label"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_CHILD_SEAT');?></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control show-spinner" id="addForm-child_seat" name="child_seat" data-max="3"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="addForm-maxi_cosi" class="col-md-3 control-label"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_MAXI_COSI');?></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control show-spinner" id="addForm-maxi_cosi" name="maxi_cosi" data-max="3"/>
                                <p class="form-control-static"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_MAXI_COSI_CHILD_SEAT_INFO');?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="addForm-child_seat_elevation" class="col-md-3 control-label"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_CHILD_SEAT_ELEVATION');?>.</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control show-spinner" id="addForm-child_seat_elevation" name="child_seat_elevation" data-max="3"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="addForm-additionaladdresses_id" class="col-md-3 control-label"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_ADDITIONALADDRESSES_ID');?></label>
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
                            <label for="addForm-paymentmethod_id" class="col-md-3 control-label"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_PAYMENTMETHOD_ID');?><span class="asterisk">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control" name="paymentmethod_id" id="addForm-paymentmethod_id">
                                  <?php
                                    foreach($this->paymentmethods as $paymentmethod) {
                                        /*echo '<option value="'.$paymentmethod->paymentmethod_id.'" data-paymentmethod_price="'.$paymentmethod->price.'">'.$paymentmethod->name.' (+ € '.number_format($paymentmethod->price,2).')'.'</option>';*/	
                                        //Wenn bei Paymentmethod gespeichert ist dass nur der Admin sie auswaehlen darf (only_admin == 1)
                                        if(!$paymentmethod->only_admin || (JFactory::getUser()->authorise('cabsystem.admin', 'com_cabsystem'))) {
                                            echo '<option value="'.$paymentmethod->paymentmethod_id.'" data-paymentmethod_price="'.$paymentmethod->price.'">'.JText::_($paymentmethod->language_string).'</option>';
                                        }
                                    }
                                  ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="addForm-comment" class="col-md-3 control-label"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_COMMENT');?></label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="comment" id="addForm-comment" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="addForm-postorder" class="col-md-3 control-label"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_POSTORDER');?></label>
                            <div class="col-md-9">
                                <select class="form-control" name="postorder" id="addForm-postorder">
                                  <?php
                                    echo '<option value="0" selected="selected">'.JText::_('COM_CABSYSTEM_MODAL_ADD_POSTORDER_NO').'</option>';
                                    echo '<option value="1">'.JText::_('COM_CABSYSTEM_MODAL_ADD_POSTORDER_YES').'</option>';
                                  ?>
                                </select>
                            </div>
                        </div>
                        <?php
                        //Wenn Admin dann soll Preis-Override Feld angezeigt werden
                        if (JFactory::getUser()->authorise('cabsystem.admin', 'com_cabsystem')) {
                        ?>
                        <div class="form-group">
                            <label for="addForm-price_override" class="col-md-3 control-label"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_PRICE_OVERRIDE');?></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="addForm-price_override" name="price_override"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-9 col-sm-offset-3"><li class="fa fa-warning"></li> <?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_PRICE_OVERRIDE_INFO');?></div>
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
                                    <?php echo JText::sprintf('COM_CABSYSTEM_MODAL_ADD_TOS_ACCEPTED',"/agb.html");?>
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
					<div class="panel-heading"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_POSTORDER');?></div>
					<div class="panel-body">
                    	<p><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_POSTORDER_INFO');?></p>
                        <div class="form-group">
                            <label for="addForm-postorder_date" class="col-md-3 control-label"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_POSTORDER_DATE');?><span class="asterisk">*</span></label>
                            <div class="col-md-9">
                                <div class='input-group date' id='addForm-postorder_date-picker' data-date-format="YYYY-MM-DD" data-min-date="<?php echo (JFactory::getUser()->authorise('cabsystem.admin', 'com_cabsystem')) ? 'false' : 'true';?>">
                                    <input type='text' class="form-control" name="postorder_date" id="addForm-postorder_date" readonly="readonly"/>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="addForm-postorder_time" class="col-md-3 control-label"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_POSTORDER_TIME');?><span class="asterisk">*</span></label>
                            <div class="col-md-9">
                                <div class='input-group date' id='addForm-postorder_time-picker' data-date-format="HH:mm:ss">
                                    <input type='text' class="form-control" name="postorder_time" id="addForm-postorder_time" readonly="readonly" data-check="true"/>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-offset-3 col-md-9">
                        	<p class="form-static-control"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_POSTORDER_FLIGHT_INFO');?></p>
                        </div>
                        <div class="form-group">
                            <label for="addForm-postorder_from_flight_id" class="col-md-3 control-label"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_POSTORDER_FROM_FLIGHT_ID');?><span class="asterisk">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control" name="postorder_from_flight_id" id="addForm-postorder_from_flight_id">
                                  <?php
                                    echo '<option value="other">'.JText::_('COM_CABSYSTEM_OTHER_F').'</option>';
                                    foreach($this->destination_cities as $destination_city) {
                                        echo '<option value="'.$destination_city->city_id.'">'.$destination_city->name.'</option>';	
                                    }
                                  ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="addForm-postorder_flight_number" class="col-md-3 control-label"><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_POSTORDER_FLIGHT_NUMBER');?></label>
                            <div class="col-md-9">
                                <input type='hidden' class="form-control" name="postorder_flight_number" id="addForm-postorder_flight_number" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-right"><small><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_PRICE_INFO');?></small></div>
                <input type="hidden" name="price" id="addForm-price" value="NULL" />  
                <input type="hidden" name="view" value="order" />
                <input type="hidden" name="model" value="Order" />
                <input type="hidden" name="item" value="order" />
                <input type="hidden" name="table" value="orders" />
			</div>
			<div class="modal-footer">
            	<p><?php echo JText::_('COM_CABSYSTEM_MODAL_ADD_PRICE_INFO2');?> <strong><span class="addForm-pricedisplay"></span></strong></p>
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo JText::_('COM_CABSYSTEM_ABORT');?></button>
				<button type="button" class="btn btn-primary" id="addOrder"><?php echo JText::_('COM_CABSYSTEM_BOOK');?></button>
				</form>
			</div>
		</div>
	</div>
</div>