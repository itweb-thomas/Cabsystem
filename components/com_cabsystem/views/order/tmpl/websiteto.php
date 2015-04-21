<h1><?php echo JText::_('COM_CABSYSTEM_WEBSITETO_HEADLINE');?></h1>
<div class="col-sm-4 col-md-offset-4 margin-top15">
     <button id="getAddModalOrder" type="button" class="btn btn-primary btn-md btn-block" data-toggle="modal" data-webform="true" data-target="#addOrderModal" data-preselection="to_airport"><i class="fa fa-car"></i> <?php echo JText::_('COM_CABSYSTEM_BOOK_ONLINE'); ?></button>
	<?php echo $this->_orderAddView->render(); ?>
</div>
<div class="col-sm-8 col-sm-offset-2">
<p class="text-center"><?php echo JText::_('COM_CABSYSTEM_WEBSITETO_INTRO_TEXT');?></p>
</div>
<div class="col-sm-8 col-sm-offset-2">
	<div class="col-sm-3">
		<div class="alert alert-warning text-center" role="alert"><?php echo JText::_('COM_CABSYSTEM_WEBSITEFROM_WARNING1_TEXT');?></div>
	</div>
	<div class="col-sm-3">
		<div class="alert alert-warning text-center" role="alert"><?php echo JText::_('COM_CABSYSTEM_WEBSITEFROM_WARNING2_TEXT');?></div>
	</div>
	<div class="col-sm-3">
		<div class="alert alert-warning text-center" role="alert"><?php echo JText::_('COM_CABSYSTEM_WEBSITEFROM_WARNING3_TEXT');?></div>
	</div>
	<div class="col-sm-3">
		<div class="alert alert-warning text-center" role="alert"><?php echo JText::_('COM_CABSYSTEM_WEBSITETO_WARNING4_TEXT');?></div>
	</div>
</div>