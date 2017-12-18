<?php 
// No direct access
defined('_JEXEC') or die; ?>

<table class="table table-bordered">
	<thead>
		<tr>
			<th><?php echo JText::_('COM_OEVENTS_DATE'); ?></th>
			<th><?php echo JText::_('COM_OEVENTS_EVENT'); ?></th>
			<th><?php echo JText::_('COM_OEVENTS_VENUE'); ?></th>
			<th><?php echo JText::_('COM_OEVENTS_LEVEL'); ?></th>
			<th><?php echo JText::_('COM_OEVENTS_CLUB'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php if (sizeof($eventList) > 0) { foreach ($eventList as $event) : ?>
		<tr>
			<td><?php echo date("D jS M Y", strtotime($event['date'])); ?></td>
			<td><a href="<?php echo $event['url']; ?>"><?php echo $event['title']; ?></a></td>
			<td><?php echo $event['venue']; ?></td>
			<td><?php echo JText::_("COM_OEVENTS_EVENT_LEVEL_" . $event['level']); ?></td>
			<td><?php if ($event['clubUrl'] != "") {?>
				<a href="<?php echo $event['clubUrl']; ?>"><?php echo $event['club']; ?></a>
				<?php } else { ?>
				<?php echo $event['club']; ?>
				<?php } ?>
			</td>
		</tr>
		<?php endforeach; } else { ?>
		<tr>
			<td colspan="5"><?php echo JText::_("COM_OEVENTS_NO_EVENTS"); ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>