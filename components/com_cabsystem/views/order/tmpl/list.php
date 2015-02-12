
<h1><?php echo JText::_('COM_CABSYSTEM_ORDER_LIST_TITLE'); ?></h1>

<div class="content-toolbar">
	<button id="getAddModalOrder" type="button" class="btn btn-default btn-sm addFlight" data-toggle="modal" data-target="#addOrderModal"><i class="fa fa-plus"></i> <?php echo JText::_('COM_CABSYSTEM_ADD'); ?></button>
	<?php echo $this->_orderAddView->render(); ?>

	<button id="getEditModalOrder" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i> <?php echo JText::_('COM_CABSYSTEM_EDIT'); ?></button>
    
    <button id="getSetDriverModalOrder" class="btn btn-default btn-sm"><i class="fa fa-user"></i> <?php echo JText::_('COM_CABSYSTEM_SET_DRIVER'); ?></button>    

	<button id="cancelOrder" class="btn btn-default btn-sm"><i class="fa fa-minus-circle"></i> <?php echo JText::_('COM_CABSYSTEM_CANCEL'); ?></button>

	<button id="getDeleteModalOrder" class="btn btn-default btn-sm" data-toggle="modal" data-target="#deleteConfirm"><i class="fa fa-times"></i> <?php echo JText::_('COM_CABSYSTEM_DELETE'); ?></button>
    <div class="modal fade" id="deleteConfirm" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="deleteConfirmLabel">Bestellung löschen</h4>
          </div>
          <div class="modal-body">
            <p>Möchten Sie diesen Bestellungn wirklich löschen?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">nein</button>
            <button type="button" id="deleteOrder" class="btn btn-primary">ja</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div>
    
    <!-- HILFE BUTTON -->
    <button class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#helpModal">
      <i class="fa fa-question-circle"></i> Hilfe
    </button>
    
    <!-- Modal -->
    <div class="modal fade" id="helpModal" tabindex="-1" role="dialog" aria-labelledby="helpModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Schließen</span></button>
            <h4 class="modal-title" id="Label">Hilfe</h4>
          </div>
          <div class="modal-body">
          	<div class="media">
              <i class="fa fa-taxi fa-2x pull-left"></i>
              <div class="media-body">
                <h4 class="media-heading">Neue Bestellung</h4>
                Eine Bestellung hat diesen Status wenn Sie neu hinzugefügt wurde oder einer älteren Bestellung kein Fahrer zugewiesen wurde. Eine solche Fahrt muss einem Fahrer zugewiesen werden.
              </div>
            </div>
          	<div class="media">
              <i class="icon-pending fa fa-clock-o fa-2x pull-left"></i>
              <div class="media-body">
                <h4 class="media-heading">Warte auf Antwort vom Fahrer</h4>
                Wenn eine Fahrt einem Fahrer zugewiesen wurde muss dieser die Bestellung annehmen oder ablehnen. Bis er das tut ist die Bestellung in diesem Status.
              </div>
            </div>
          	<div class="media">
              <i class="icon-active fa fa-check-circle fa-2x pull-left"></i>
              <div class="media-body">
                <h4 class="media-heading">Angenommen</h4>
                Eine Fahrt hat diesen Status wenn der zugewiesene Fahrer die Email bestätigt und die Fahrt angenommen hat.
              </div>
            </div>
          	<div class="media">
              <i class="icon-inactive fa fa-minus-circle fa-2x pull-left"></i>
              <div class="media-body">
                <h4 class="media-heading">Storniert</h4>
                Eine Fahrt hat diesen Status wenn sie storniert wurde. Der zuletzt zugewiesene Fahrer bleibt gespeichert.
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
          </div>
        </div>
      </div>
    </div>

</div>

<div class="content-panel">

	<table id="dataTable" class="table table-striped table-hover table-condensed table-responsive">

		<thead>
			<tr>
                <th>Nr.</th>
            	<th>Status</th>
                <th>Status</th>
                <th>Auto</th>
            	<th>Timestamp</th>
            	<th>Datum/Zeit</th>
            	<th>Abgelaufen</th>
                <th>Von</th>
                <th>Nach</th>
                <th>Zstadr.</th>
                <th>Kunde</th>
                <th>Anm.</th>
                <th>Preis</th>
            </tr>
		</thead>

		<tbody>

			<?php

				for($i = 0, $n = count($this->orders); $i < $n; $i++)

				{

					$this->_orderListView->order = $this->orders[$i];

					echo $this->_orderListView->render();

				}

			?>

		</tbody>

	</table>

</div>