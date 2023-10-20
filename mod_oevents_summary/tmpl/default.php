<?php 
// No direct access
defined('_JEXEC') or die; 

use Joomla\CMS\Language\Text;

$messageKey = '';
if ($leadIn->eventCount > 2) {
    $messageKey = 'MOD_OEVENTS_SUMMARY_LEAD_IN';
} else if ($leadIn->eventCount == 1) {
    $messageKey = 'MOD_OEVENTS_SUMMARY_LEAD_IN_SINGLE';
}

if ($leadIn->eventCount > 0) { ?>
    <small><?php echo Text::sprintf($messageKey, $leadIn->eventListURL, $leadIn->eventCount); ?></small>
<?php } ?>
