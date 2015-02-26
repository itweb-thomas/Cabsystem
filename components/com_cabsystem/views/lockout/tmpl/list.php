
<h1><?php echo JText::_('COM_CABSYSTEM_LOCKOUT_LIST_TITLE'); ?></h1>

<div class="content-toolbar">

	<button id="lockLockout" class="btn btn-default btn-sm"><i class="fa fa-lock"></i> <?php echo JText::_('COM_CABSYSTEM_LOCK'); ?></button>

	<button id="unlockLockout" class="btn btn-default btn-sm"><i class="fa fa-unlock"></i> <?php echo JText::_('COM_CABSYSTEM_UNLOCK'); ?></button>

</div>

<div class="content-panel">

	<table id="dataTable" class="table table-striped table-hover table-condensed">

		<thead>
			<tr>
            	<th>Wann</th>
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