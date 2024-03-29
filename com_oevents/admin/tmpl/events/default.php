<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_oevents
 */
 
namespace OEvents\Component\OEvents\Administrator\View\Events;

use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\HTML\HTMLHelper;

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// Stringify the levels
$array = $this->params->get('eventLevel');
// FIXME: Array can be null on initial installations
$prettyArray = array_map(function ($event_level) {
	// Not great that this mapping has to be in the function..
	$levelMapping = [
		'1' => 'COM_OEVENTS_EVENT_LEVEL_1', 
		'2' => 'COM_OEVENTS_EVENT_LEVEL_2', 
		'3' => 'COM_OEVENTS_EVENT_LEVEL_3', 
		'4' => 'COM_OEVENTS_EVENT_LEVEL_4', 
		'5' => 'COM_OEVENTS_EVENT_LEVEL_5'
	];

	return(Text::_($levelMapping[$event_level]));
}, $array);

$last  = array_slice($prettyArray, -1);
$first = join(', ', array_slice($prettyArray, 0, -1));
$both  = array_filter(array_merge([$first], $last), 'strlen');
$stringifiedList = join(' or ', $both); 

?>

<div class="row">
	<div class="col order-last card">
		<div class="card-body">
			<h3 class="card-title"><?php echo Text::_('COM_OEVENTS_CONFIG_BOX_TITLE'); ?></h3>
			<p class="card-text"><?php echo Text::_('COM_OEVENTS_CONFIG_BOX_DESCRIPTION'); ?></p>
			<table class="table table-sm fs-6">
				<tobdy>
					<tr>
						<th scope="row"><?php echo Text::_('COM_OEVENTS_CONFIG_FIELD_POSTCODE_LABEL'); ?></th>
						<td><?php echo $this->params->get('postcode'); ?></td>
					</tr>
					<tr>
						<th scope="row"><?php echo Text::_('COM_OEVENTS_CONFIG_FIELD_RADIUS_LABEL'); ?></th>
						<td><?php echo $this->params->get('radius'); ?></td>
					</tr>
					<tr>
						<th scope="row"><?php echo Text::_('COM_OEVENTS_CONFIG_FIELD_LOOK_AHEAD_LABEL'); ?></th>
						<td><?php echo $this->params->get('lookAhead'); ?></td>
					</tr>
					<tr>
						<th scope="row"><?php echo Text::_('COM_OEVENTS_CONFIG_FIELD_EVENT_LEVELS_LABEL'); ?></th>
						<td><?php echo $stringifiedList; ?></td>
					</tr>
				</tobdy>
			</table>
			<p class="card-text"><?php echo Text::_('COM_OEVENTS_CONFIG_SCHEDULER'); ?></p>
		</div>
	</div>
	<div class="col">
		<h1><?php echo Text::_('COM_OEVENTS'); ?></h1>
		<?php echo Text::_('COM_OEVENTS_DESCRIPTION'); ?>
	</div>
</div>
<div class="row">
	<div>
		<form action="index.php?option=com_oevents&amp;view=events" method="post" id="adminForm" name="adminForm">
			<fieldset>
				<legend><?php echo Text::_('COM_OEVENTS_BACKEND_TABLE_HEADER'); ?></legend>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th><?php echo Text::_('COM_OEVENTS_SELECT'); ?></th>
							<th><?php echo Text::_('COM_OEVENTS_DATE'); ?></th>
							<th><?php echo Text::_('COM_OEVENTS_EVENT'); ?></th>
							<th><?php echo Text::_('COM_OEVENTS_VENUE'); ?></th>
							<th><?php echo Text::_('COM_OEVENTS_LEVEL'); ?></th>
							<th><?php echo Text::_('COM_OEVENTS_CLUB'); ?></th>
							<th><?php echo Text::_('COM_OEVENTS_STATUS'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php if (!empty($this->items)) : ?>
							<?php foreach ($this->items as $i => $row) : 
								$editLink = Route::_('index.php?option=com_oevents&task=event.edit&event_id=' . $row->event_id); ?>
								<tr>
									<td><?php echo HTMLHelper::_('grid.id', $i, $row->event_id); ?></td>
									<td><?php echo $row->date; ?></td>
									<td><a href="<?php echo $editLink; ?>" title="<?php echo Text::_('COM_OEVENTS_EDIT_EVENT'); ?>"><?php echo $row->title; ?></a></td>
									<td><?php echo $row->venue; ?></td>
									<td><?php if (!empty($row->level)) { 
										echo Text::_("COM_OEVENTS_EVENT_LEVEL_" . $row->level);
									 } ?></td>
									<td><?php echo $row->club; ?></td>
									<td><?php if ($row->status == 1) {
										echo "AUTO";
									} elseif ($row->status == 0) {
										echo "MANUAL";
									} elseif ($row->status == 2) {
										echo "OVERIDDEN";
									} else {
										echo "UNKNOWN";
									} ?></td>
								</tr>
							<?php endforeach; ?>
						<?php else : ?>
							<tr>
								<td colspan="7" style="text-align: center"><?php echo Text::_('COM_OEVENTS_NO_EVENTS'); ?></td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
				<input type="hidden" name="task" value=""/>
				<input type="hidden" name="boxchecked" value="0"/>
				<?php echo HTMLHelper::_('form.token'); ?>
			</fieldset>
		</form>
	</div>
</div>

