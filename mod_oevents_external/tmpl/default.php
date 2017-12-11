<?php 
// No direct access
defined('_JEXEC') or die; ?>

<table class="table table-bordered">
	<thead>
		<tr>
			<th>Date</th>
			<th>Event</th>
			<th>Venue</th>
			<th>Level</th>
			<th>Club</th>
		</tr>
	</thead>
	<tbody>
		<?php if (sizeof($eventList) > 0) { foreach ($eventList as $event) : ?>
		<tr>
			<td><?php echo date("D jS M Y", strtotime($event['date'])); ?></td>
			<td><a href="<?php echo $event['url']; ?>"><?php echo $event['title']; ?></a></td>
			<td><?php echo $event['venue']; ?></td>
			<td><?php echo $event['level']; ?></td>
			<td><?php if ($event['clubUrl'] != "") {?>
				<a href="<?php echo $event['clubUrl']; ?>"><?php echo $event['club']; ?></a>
				<?php } else { ?>
				<?php echo $event['club']; ?>
				<?php } ?>
			</td>
		</tr>
		<?php endforeach; } else { ?>
		<tr>
			<td colspan="5">No other events of interest found</td>
		</tr>
		<?php } ?>
	</tbody>
</table>