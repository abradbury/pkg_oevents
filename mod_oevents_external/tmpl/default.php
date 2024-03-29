<?php 
// No direct access
defined('_JEXEC') or die; 

use Joomla\CMS\Language\Text;
?>

<table class="table table-bordered">
	<thead>
		<tr>
			<th><?php echo Text::_('COM_OEVENTS_DATE'); ?></th>
			<th><?php echo Text::_('COM_OEVENTS_EVENT'); ?></th>
			<th><?php echo Text::_('COM_OEVENTS_VENUE'); ?></th>
			<th><?php echo Text::_('COM_OEVENTS_LEVEL'); ?></th>
			<th><?php echo Text::_('COM_OEVENTS_CLUB'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php if (sizeof($events) > 0) { foreach ($events as $event) : ?>
		<tr>
			<td><?php echo $event['formattedDate']; ?></td>
			<td><a href="<?php echo $event['url']; ?>"><?php echo $event['title']; ?></a></td>
			<td><?php echo $event['venue']; ?></td>
			<td><?php if (!empty($event['level'])) {
				echo Text::_("COM_OEVENTS_EVENT_LEVEL_" . $event['level']);
			} ?></td>
			<td><?php if ($event['clubUrl'] != "") {?>
				<a href="<?php echo $event['clubUrl']; ?>"><?php echo $event['club']; ?></a>
				<?php } else { ?>
				<?php echo $event['club']; ?>
				<?php } ?>
			</td>
		</tr>
		<?php endforeach; } else { ?>
		<tr>
			<td colspan="5"><?php echo Text::_("COM_OEVENTS_NO_EVENTS"); ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>