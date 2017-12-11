<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_oevents
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// Stringify the levels
$array = $this->params->get('eventLevel');
$last  = array_slice($array, -1);
$first = join(', ', array_slice($array, 0, -1));
$both  = array_filter(array_merge(array($first), $last), 'strlen');
$stringifiedList = join(' or ', $both); 

?>

<div class="row-fluid">
	<div class="span3 well">
		<h3>Configuration</h3>
		<p>Showing events within <strong><?php echo $this->params->get('radius'); ?></strong> miles of <strong><?php echo $this->params->get('postcode'); ?></strong> that are of level <strong><?php echo $stringifiedList; ?></strong> where levels are as follows: </p>
		<ul>
			<li><strong>1</strong>: National (Lv. A)</li>
			<li><strong>2</strong>: Regional (Lv. B)</li>
			<li><strong>3</strong>: Regional (Lv. C)</li>
			<li><strong>4</strong>: Local (Lv. D)</li>
			<li><strong>5</strong>: International</li>
		</ul>
		<p>To configure these options, select the options menu from the toolbar.</p>
		<p>OEvents is currently scheduled to check for updates <strong>once per day</strong> using a cron job that runs at <strong>3:00am</strong>. To alter the frequency of this, please contact the website administrator.</p>
	</div>
	<div class="span9">
		<h1>OEvents</h1>
		<p><?php echo JText::_('COM_OEVENTS_DESCRIPTION'); ?></p>
		<p>Whilst it is possible to edit or remove events acquired from remote sources (marked as AUTO), currently these updates will be discarded when OEvents next updates.</p>
	
		<form action="index.php?option=com_oevents&amp;view=events" method="post" id="adminForm" name="adminForm">
			<fieldset>
				<legend>External Events</legend>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th><?php echo JText::_('COM_OEVENTS_SELECT'); ?></th>
							<th><?php echo JText::_('COM_OEVENTS_DATE'); ?></th>
							<th><?php echo JText::_('COM_OEVENTS_EVENT'); ?></th>
							<th><?php echo JText::_('COM_OEVENTS_VENUE'); ?></th>
							<th><?php echo JText::_('COM_OEVENTS_LEVEL'); ?></th>
							<th><?php echo JText::_('COM_OEVENTS_CLUB'); ?></th>
							<th><?php echo JText::_('COM_OEVENTS_STATUS'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php if (!empty($this->items)) : ?>
							<?php foreach ($this->items as $i => $row) : 
								$editLink = JRoute::_('index.php?option=com_oevents&task=event.edit&event_id=' . $row->event_id); ?>
								<tr>
									<td><?php echo JHtml::_('grid.id', $i, $row->event_id); ?></td>
									<td><?php echo $row->date; ?></td>
									<td><a href="<?php echo $editLink; ?>" title="<?php echo JText::_('COM_OEVENTS_EDIT_EVENT'); ?>"><?php echo $row->title; ?></a></td>
									<td><?php echo $row->venue; ?></td>
									<td><?php echo $row->level; ?></td>
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
								<td colspan="7" style="text-align: center">No upcoming external events found</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
				<input type="hidden" name="task" value=""/>
				<input type="hidden" name="boxchecked" value="0"/>
				<?php echo JHtml::_('form.token'); ?>
			</fieldset>
		</form>
	</div>
</div>

