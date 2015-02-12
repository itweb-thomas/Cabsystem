<h1><?php echo JText::_('COM_CABSYSTEM_DISTRICT_LIST_TITLE'); ?></h1>

<div class="content-toolbar">

	<button type="button" class="btn btn-default btn-sm addDistrict" data-toggle="modal" data-target="#addDistrictModal"><i class="fa fa-plus"></i> <?php echo JText::_('COM_CABSYSTEM_ADD'); ?></button>
	<?php echo $this->_districtAddView->render(); ?>

	<button id="getEditModalDistrict" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i> <?php echo JText::_('COM_CABSYSTEM_EDIT'); ?></button>

	<button id="getDeleteModalDistrict" class="btn btn-default btn-sm" data-toggle="modal" data-target="#deleteConfirm"><i class="fa fa-times"></i> <?php echo JText::_('COM_CABSYSTEM_DELETE'); ?></button>
    <div class="modal fade" id="deleteConfirm" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="deleteConfirmLabel">Bezirk/Preis löschen</h4>
          </div>
          <div class="modal-body">
            <p>Möchten Sie diesen Bezirk/Preis wirklich löschen?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">nein</button>
            <button type="button" id="deleteDistrict" class="btn btn-primary">ja</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div>

</div>

<div class="content-panel">

	<table id="dataTable" class="table table-striped table-hover table-condensed">

		<thead>
			<tr>
                <th>Postleitzahl</th>
                <th>Bezirk</th>
                <th>Ort</th>
                <?php
				foreach($this->cartypes as $cartype) 
				{
					echo '<th>'.$cartype->name.' Normal</th>';	
				}
				foreach($this->cartypes as $cartype) 
				{
					echo '<th>'.$cartype->name.' Zusatzadr.</th>';	
				}
				?>
            </tr>
		</thead>

		<tbody>

			<?php

				for($i = 0, $n = count($this->districts); $i < $n; $i++)

				{

					$this->_districtListView->district = $this->districts[$i];
					$this->_districtModel->getPrices($this->_districtListView->district);

					echo $this->_districtListView->render();

				}

			?>

		</tbody>

	</table>

</div>