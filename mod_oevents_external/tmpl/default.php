<?php
// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

// Event data can come from a remote website, so escape everything that is output
$escape = static function ($value) {
	return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};
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
			<td><?php echo $escape($event['formattedDate']); ?></td>
			<td><?php if ($event['url'] !== '') { ?>
				<a href="<?php echo $escape($event['url']); ?>"><?php echo $escape($event['title']); ?></a>
				<?php } else { ?>
				<?php echo $escape($event['title']); ?>
				<?php } ?>
			</td>
			<td><?php echo $escape($event['venue']); ?></td>
			<td><?php if (!empty($event['level'])) {
				echo $escape(Text::_("COM_OEVENTS_EVENT_LEVEL_" . (int) $event['level']));
			} ?></td>
			<td><?php if ($event['clubUrl'] !== '') { ?>
				<a href="<?php echo $escape($event['clubUrl']); ?>"><?php echo $escape($event['club']); ?></a>
				<?php } else { ?>
				<?php echo $escape($event['club']); ?>
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
