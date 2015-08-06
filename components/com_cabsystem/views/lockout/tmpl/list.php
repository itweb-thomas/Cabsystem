
<h1><?php echo JText::_('COM_CABSYSTEM_LOCKOUT_LIST_TITLE'); ?></h1>

<div class="content-toolbar">

	<button type="button" class="btn btn-default btn-sm addLockout" data-toggle="modal" data-target="#addLockoutModal"><i class="fa fa-plus"></i> <?php echo JText::_('COM_CABSYSTEM_ADD'); ?></button>
	<?php echo $this->_lockoutAddView->render(); ?>

	<button id="getEditModalLockout" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i> <?php echo JText::_('COM_CABSYSTEM_EDIT'); ?></button>

	<button id="getDeleteModalLockout" class="btn btn-default btn-sm" data-toggle="modal" data-target="#deleteConfirm"><i class="fa fa-times"></i> <?php echo JText::_('COM_CABSYSTEM_DELETE'); ?></button>
	<div class="modal fade" id="deleteConfirm" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="deleteConfirmLabel">Fahrer löschen</h4>
				</div>
				<div class="modal-body">
					<p>Möchten Sie diese Sperre wirklich löschen?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">nein</button>
					<button type="button" id="deleteLockout" class="btn btn-primary">ja</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div>
	
	<button id="lockLockout" class="btn btn-default btn-sm"><i class="fa fa-lock"></i> <?php echo JText::_('COM_CABSYSTEM_LOCK'); ?></button>

	<button id="unlockLockout" class="btn btn-default btn-sm"><i class="fa fa-unlock"></i> <?php echo JText::_('COM_CABSYSTEM_UNLOCK'); ?></button>

</div>

<div class="content-panel">

	<table id="dataTable" class="table table-striped table-hover table-condensed">

		<thead>
			<tr>
				<th>Timestamp</th>
				<th>Datum</th>
				<th>Wann</th>
				<th>Typ</th>
                <th>Status</th>
            </tr>
		</thead>

		<tbody>

			<?php

				for($i = 0, $n = count($this->lockouts); $i < $n; $i++)

				{

					$this->_lockoutListView->lockout = $this->lockouts[$i];

					echo $this->_lockoutListView->render();

				}

			?>

		</tbody>

	</table>

</div>