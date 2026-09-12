<?php
// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

$messageKey = '';
if ($leadIn->eventCount > 1) {
    $messageKey = 'MOD_OEVENTS_SUMMARY_LEAD_IN';
} else if ($leadIn->eventCount == 1) {
    $messageKey = 'MOD_OEVENTS_SUMMARY_LEAD_IN_SINGLE';
}

if ($leadIn->eventCount > 0) { ?>
    <small><?php echo Text::sprintf($messageKey, htmlspecialchars((string) $leadIn->eventListURL, ENT_QUOTES, 'UTF-8'), (int) $leadIn->eventCount); ?></small>
<?php } ?>
