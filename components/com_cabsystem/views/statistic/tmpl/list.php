<h1><?php echo JText::_('COM_CABSYSTEM_STATISTIC_LIST_TITLE'); ?></h1>
<form id="export-form" action="<?php echo JRoute::_(JUri::root().'index.php?option=com_cabsystem&controller=ajax&task=exportOrders&format=raw&tmpl=component');?>" method="post">
<div class="panel panel-default">
	<div class="panel-body">
		<div class="col-md-2">
        	<label for="from-date" class="control-label">Von</label>
			<div class='input-group date' id='from-date-input' data-date-format="YYYY-MM-DD">
                <input type='text' class="form-control" name="from-date" id="from-date"/>
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
        </div>
        <div class="col-md-2">
        	<label for="to-date" class="control-label">Bis</label>
            <div class='input-group date' id='to-date-input' data-date-format="YYYY-MM-DD">
                <input type='text' class="form-control" name="to-date" id="to-date"/>
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
        </div>
        <div class="col-md-2">
        	<label for="driver" class="control-label">Fahrer</label>
            <select class="form-control" name="driver" id="driver">
              <?php
                echo '<option value="">alle</option>';
                foreach($this->drivers as $driver) {
                    echo '<option value="'.$driver->driver_id.'">'.$driver->name.'</option>';	
                }
              ?>
            </select>
        </div>
        <div class="col-md-2">
        	<label for="paymentmethod" class="control-label">Bezahlvariante</label>
            <select class="form-control" name="paymentmethod" id="paymentmethod">
              <?php
                echo '<option value="">alle</option>';
                foreach($this->paymentmethods as $paymentmethod) {
                	echo '<option value="'.$paymentmethod->paymentmethod_id.'">'.$paymentmethod->name.'</option>';	              
				}	
              ?>
            </select>
        </div>
        <div class="col-md-4">
        	<label class="control-label">&nbsp;</label>
        	<button id="export2" type="submit" class="btn btn-primary pull-right form-control">Exportieren und als .csv herunterladen</button>
        </div>
	</div>
</div>
<div class="panel panel-default">
	<div class="panel-body">
		<p>Welche Felder sollen in der exportierten CSV Datei angezeigt werden?</p>
        <div class="row">
            <div class="col-md-4">
                <div class="checkbox"><label><input type="checkbox" name="fields[]" value="id"> Nummer</label></div>
                <div class="checkbox"><label><input type="checkbox" name="fields[]" value="date"> Datum</label></div>
                <div class="checkbox"><label><input type="checkbox" name="fields[]" value="time"> Uhrzeit</label></div>
                <div class="checkbox"><label><input type="checkbox" name="fields[]" value="from"> Von Adresse</label></div>
                <div class="checkbox"><label><input type="checkbox" name="fields[]" value="to"> Nach Adresse</label></div>
                <div class="checkbox"><label><input type="checkbox" name="fields[]" value="additional_addresses"> Zusatzadressen</label></div>
            </div>
            <div class="col-md-4">
                <div class="checkbox"><label><input type="checkbox" name="fields[]" value="customer"> Kunde</label></div>
                <div class="checkbox"><label><input type="checkbox" name="fields[]" value="comment"> Anmerkung</label></div>
                <div class="checkbox"><label><input type="checkbox" name="fields[]" value="price"> Preis</label></div>
                <div class="checkbox"><label><input type="checkbox" name="fields[]" value="paymentmethod"> Bezahlvariante</label></div>
                <div class="checkbox"><label><input type="checkbox" name="fields[]" value="luggage"> Anzahl Koffer</label></div>
                <div class="checkbox"><label><input type="checkbox" name="fields[]" value="handluggage"> Anzahl Handgepäck</label></div>
            </div>
            <div class="col-md-4">
                <div class="checkbox"><label><input type="checkbox" name="fields[]" value="child_seat"> Anzahl Kindersitze</label></div>
                <div class="checkbox"><label><input type="checkbox" name="fields[]" value="child_seat_elevation"> Anzahl Kindersitzerhöhungen</label></div>
                <div class="checkbox"><label><input type="checkbox" name="fields[]" value="maxi_cosi"> Anzahl Maxi Cosi</label></div>
                <div class="checkbox"><label><input type="checkbox" name="fields[]" value="creator"> Erstellt von</label></div>
                <div class="checkbox"><label><input type="checkbox" name="fields[]" value="cartype"> Autotyp</label></div>
                <div class="checkbox"><label><input type="checkbox" name="fields[]" value="driver"> Fahrer</label></div>
            </div>
		</div>
        <div class="row">
            <div class="col-md-12 margin-top05x">
                <button id="check-all" type="button" class="btn btn-default">Alle auswählen</button>
                <button id="uncheck-all" type="button" class="btn btn-default">Alle abwählen</button>
            </div>
        </div>
	</div>
</div>
</form>