
<h1><?php echo JText::_('COM_CABSYSTEM_CUSTOMER_LIST_TITLE'); ?></h1>

<div class="content-toolbar">

	<button type="button" class="btn btn-default btn-sm addCustomer" data-toggle="modal" data-target="#addCustomerModal"><i class="fa fa-plus"></i> <?php echo JText::_('COM_CABSYSTEM_ADD'); ?></button>
	<?php echo $this->_customerAddView->render(); ?>

	<button id="getEditModalCustomer" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i> <?php echo JText::_('COM_CABSYSTEM_EDIT'); ?></button>

	<button id="getDeleteModalCustomer" class="btn btn-default btn-sm" data-toggle="modal" data-target="#deleteConfirm"><i class="fa fa-times"></i> <?php echo JText::_('COM_CABSYSTEM_DELETE'); ?></button>
    <div class="modal fade" id="deleteConfirm" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="deleteConfirmLabel">Kunde löschen</h4>
          </div>
          <div class="modal-body">
            <p>Möchten Sie diesen Kunden wirklich löschen?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">nein</button>
            <button type="button" id="deleteCustomer" class="btn btn-primary">ja</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div>

</div>

<div class="content-panel">

	<table id="dataTable" class="table table-striped table-hover table-condensed">

		<thead>
			<tr>
            	<th>Anrede/Titel</th>
                <th>Name</th>
                <th>Adresse</th>
                <th>Telefon</th>
                <th>Email</th>
            </tr>
		</thead>

		<tbody>

			<?php

				for($i = 0, $n = count($this->customers); $i < $n; $i++)

				{

					$this->_customerListView->customer = $this->customers[$i];

					echo $this->_customerListView->render();

				}

			?>

		</tbody>

	</table>

</div>