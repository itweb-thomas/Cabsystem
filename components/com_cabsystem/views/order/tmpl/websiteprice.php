<h1>Preisberechnung</h1>
<div class="col-sm-8 col-sm-offset-2">
<p>Zur Berechnung des Preises wird das Buchungsformular verwendet, damit Sie bei einem für Sie passenden Preisangebot gleich direkt eine Fahrt buchen können. Sie sehen den berechneten Preis jeweils ganz oben und ganz unten.</p>
</div>
<div class="col-sm-4 col-md-offset-4 margin-top15">
     <button id="getAddModalOrder" type="button" class="btn btn-success btn-md btn-block" data-toggle="modal" data-webform="true" data-target="#addOrderModal"><i class="fa fa-calculator"></i> <?php echo JText::_('COM_CABSYSTEM_CALC_PRICE'); ?></button>
	<?php echo $this->_orderAddView->render(); ?>
</div>
<div class="col-sm-8 col-sm-offset-2">
<p class="text-center">Sie möchten unverbindlich überprüfen wie viel Sie eine Fahrt vom bzw. zum Flughafen kostet? Benutzen Sie unser Online-Formular - der Preis wird dynamisch aufgrund Ihrer Eingaben berechnet. Die Übermittlung der Daten erfolgt erst bei einer etwaigen Buchung - davor ist alles unverbindlich.</p>
</div>
<div class="col-sm-8 col-sm-offset-2">
	<div class="col-sm-3">
    	<div class="alert alert-warning text-center" role="alert">Fahrten, die bis 22.00 Uhr am selben Tag
stattfinden sollen, mindestens 4 Stunden vorher reservieren.</div>
	</div>
	<div class="col-sm-3">
		<div class="alert alert-warning text-center" role="alert">Fahrten, die zwischen 22.00 und 6.00 stattfinden sollen, mindestens 10 Stunden vorher reservieren.</div>
	</div>
	<div class="col-sm-6">
		<div class="alert alert-warning text-center" role="alert">Im Falle zu später Reservierung können wir die Verfügbarkeit eines Wagens - trotz automatischer Bestätigung über Internet - nicht garantieren.</div>
	</div>
</div>