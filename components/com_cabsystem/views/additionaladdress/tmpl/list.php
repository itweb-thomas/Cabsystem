<h1><?php echo JText::_('COM_CABSYSTEM_ADDITIONALADDRESS_LIST_TITLE'); ?></h1>

<div class="content-toolbar">

	<button type="button" class="btn btn-default btn-sm addAdditionaladdress" data-toggle="modal" data-target="#addAdditionaladdressModal"><i class="fa fa-plus"></i> <?php echo JText::_('COM_CABSYSTEM_ADD'); ?></button>
	<?php echo $this->_additionaladdressAddView->render(); ?>

	<button id="getEditModalAdditionaladdress" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i> <?php echo JText::_('COM_CABSYSTEM_EDIT'); ?></button>

	<button id="getDeleteModalAdditionaladdress" class="btn btn-default btn-sm" data-toggle="modal" data-target="#deleteConfirm"><i class="fa fa-times"></i> <?php echo JText::_('COM_CABSYSTEM_DELETE'); ?></button>
    <div class="modal fade" id="deleteConfirm" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="deleteConfirmLabel">Zusatzadresse löschen</h4>
          </div>
          <div class="modal-body">
            <p>Möchten Sie diese Zusatzadresse wirklich löschen?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">nein</button>
            <button type="button" id="deleteAdditionaladdress" class="btn btn-primary">ja</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div>

</div>

<div class="content-panel">

	<table id="dataTable" class="table table-striped table-hover table-condensed">

		<thead>
			<tr>
                <th>Name</th>
                <th>Bezirke</th>
                <?php
				foreach($this->cartypes as $cartype) 
				{
					echo '<th>'.$cartype->name.'</th>';	
				}
				?>
            </tr>
		</thead>

		<tbody>

			<?php

				for($i = 0, $n = count($this->additionaladdresses); $i < $n; $i++)

				{

					$this->_additionaladdressListView->additionaladdress = $this->additionaladdresses[$i];
					$this->_additionaladdressModel->getAdditionalprices($this->_additionaladdressListView->additionaladdress);

					echo $this->_additionaladdressListView->render();

				}

			?>

		</tbody>

	</table>

</div>