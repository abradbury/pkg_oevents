<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_oevents
 */
 
namespace OEvents\Component\OEvents\Administrator\View\Event;

use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\HTML\HTMLHelper;

// No direct access
defined('_JEXEC') or die('Restricted access');

HTMLHelper::_('behavior.formvalidator');
?>
<form action="<?php echo Route::_('index.php?option=com_oevents&layout=edit&event_id=' . (int) $this->item->event_id); ?>"
	method="post" name="adminForm" id="adminForm" class="form-validate">
	<div class="form-horizontal">
		<fieldset class="adminform">
			<legend><?php echo(isset($this->item->event_id) ? Text::_('COM_OEVENTS_EDIT_EVENT') : Text::_('COM_OEVENTS_MANAGER_OEVENT_NEW') ); ?></legend>
			<div class="row-fluid">
				<div class="span6">
					<?php foreach ($this->form->getFieldset() as $field): ?>
						<div class="control-group">
							<div class="control-label"><?php echo $field->label; ?></div>
							<div class="controls"><?php echo $field->input; ?></div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</fieldset>
	</div>
	<input type="hidden" name="task" value="event.edit" class="validate" />
	<?php echo HTMLHelper::_('form.token'); ?>
</form>