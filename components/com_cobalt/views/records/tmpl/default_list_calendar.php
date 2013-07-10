<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');
JHtml::_('jquery.ui');

$doc = JFactory::getDocument();
$doc->addScript(JURI::root(true).'media/mint/js/fullcalendar/drag.js');
$doc->addScript(JURI::root(true).'media/mint/js/fullcalendar/fullcalendar.js');
$doc->addStyleSheet(JURI::root(true).'media/mint/js/fullcalendar/fullcalendar.css');

$params = $this->tmpl_params['list'];

?>

<div id="calendar"></div>

<script>
	jQuery('#calendar').fullCalendar({
		header: {
			left:   'today prev,next',
			center: 'title',
			right:  'month,<?php echo ($params->get('tmpl_params.time', 0) ? 'agendaWeek' : 'basicWeek');?>,<?php echo ($params->get('tmpl_params.time', 0) ? 'agendaDay' : 'basicDay');?>'
		},
		firstDay:<?php echo $params->get('tmpl_params.first_day', 7); ?>,
		viewDisplay: function(view) {
			console.log(view);
		},
		events: [
	        {
	            title  : 'event1',
	            start  : '2012-12-01'
	        },
	        {
	            title  : 'event2',
	            start  : '2012-12-05',
	            end    : '2012-12-07',
	            editable: true
	        },
	        {
	            title  : 'event3 longer name and may be even longer than any one can think',
	            start  : '2012-12-09 12:30:00',
	            allDay : false // will make the time show
	        }
	    ]
		/*events: function(start, end, callback) {
			$.ajax({
				url: 'myxmlfeed.php',
				dataType: 'json',
				data: {
					start: Math.round(start.getTime() / 1000),
					end: Math.round(end.getTime() / 1000)
				}
			}).done(function(json) {
				callback(events);
			});
		}*/
	})
</script>





























<?php
return;
if (!function_exists('cal_list_item'))
{

	function cal_list_item($item, $that, $rest = TRUE, $month_view = FALSE)
	{
		$iparams = new JRegistry(@$item->params);
		//$item->controls = $item->controls ? implode('', $item->controls) : '';
		
		$bookmark = HTMLFormatHelper::bookmark($item, $that->submission_types[$item->type_id], $iparams);
		$follow = HTMLFormatHelper::follow($item, $that->section);		
		$events = CEventsHelper::showNum('record', $item->id);
		$title = JHtml::link($item->url, $item->title, $item->nofollow ? 'rel="nofollow"' : '');
		//$title = implode(' ', $title);
		$style = '';
		if($month_view)
		{
			$item->controls = '<span class="cal_controls">'.implode('</span> <span class="cal_controls">', (array)$item->controls_notitle).'</span>';
			$title .= ' '.$item->controls;	
		}
		else 
		{
			$item->controls = $item->controls ? '<div class="menu-container"><ul class="mermoomenu"><li>
			<img alt="' . JText::_('COPTIONS') . '" src="' . JURI::root(TRUE) . '/media/mint/icons/16/gear.png" align="absmiddle"> 
			<ul style="visibility: hidden;"><li>' . implode('</li><li>', $item->controls) . '</li></ul></div>' : '';
			$title = $item->controls." ". $title;
			$style = 'style="list-type-style: none"';	
		}
		return sprintf('<li %s>%s %s %s %s %s</li>', $style, $bookmark, $follow, $events, $title, (($iparams->get('item_author') && $item->user && $rest) ? '<span class="small">' . JText::_('Written By') . ': ' . $item->user . '</span> ' : NULL));
	}
}
if (!function_exists('cal_event_body'))
{

	function cal_event_body($item, $that)
	{
		$iparams = new JRegistry(@$item->params);
		$d = array();
		$out = array();
		if ($iparams->get('item_ctime'))
		{
			$d[] = '<span class="createdate">' . $iparams->get('ctime_name') . ' ' . JHtml::_('date', $item->created, $iparams->get('item_time_format')) . '</span>';
		}
		if ($iparams->get('item_extime') && $item->expire)
		{
			$d[] = '<span class="createdate">' . $iparams->get('extime_name') . ' ' .JHtml::_('date',  $item->expire, $iparams->get('item_time_format')) . '</span>';
		}
		if ($d)
			$out[] = implode("<br />", $d);
		if ($iparams->get('rating'))
		{
			$out[] = $item->rating;
		$out[] = '<div style="clear:both"></div><br />';
		}
		
		$o = array();
		if ($iparams->get('rating'))
		{
			$o[] = JText::_('Votes') . ': ' . ($item->votes ? $item->votes : 0);
		}
		if ($iparams->get('comments'))
		{
			$o[] = JText::_('Comments') . ': ' . ($item->comments ? $item->comments : 0);
		}
		if ($iparams->get('item_hits'))
		{
			$o[] = JText::_('Hits') . ': ' . ($item->hits ? $item->hits : 0);
		}
		if ($iparams->get('favorite'))
		{
			$o[] = JText::_('Favorited') . ': ' . ($item->favorite_num ? $item->favorite_num : 0);
		}
		if (count($o))
			$out[] = '<p><span class="small">' . implode(' | ', $o) . '</span></p>';
		
		if (@$that->fields[$item->id])
		{
			$out[] = '<table class="contentpaneopen" width="100%">';
			$tab_close = false;
			foreach($that->fields[$item->id] as $field)
			{
				$fparam = new JRegistry($field->params);
				if (!$fparam->get('tabled'))
				{
					if (!$tab_close)
						$out[] = "</table>";
					$tab_close = true;
				}
				else
				{
					if ($tab_close)
						$out[] = '<table class="contentpaneopen" style="clear:both" width="100%">';
					$tab_close = false;
				}
				$out[] = $that->getFieldValue($field, $item->user_id);
			}
			if (!$tab_close)
				$out[] = "</table>";
		}

		if ($iparams->get('item_tag') && $item->tags)
		{
			$out[] = sprintf('<p><span class="small">%s: %s</span></p>', JText::_('Tags'), $item->tags);
		}
		$out = implode('', $out);
		
		$body = str_replace(array("\n", "\r", "\t"), '', $out);
		$body = preg_replace('/title=\"[^"]*\"/iU', "", $body);
		$body = str_replace('class="hasTip"', "", $body);
		$body = str_replace('"', "'", $body);
		$body = preg_replace("/>\s+</iU", "> <", $body);
		
		$tip = '<div id="MEcalendarTip' . $item->id . '" title="' . addslashes(htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8')) . '" style="display:none;">' . $body . '</div>';
		
		return $tip;
	}
}
if (!function_exists('generate_calendar'))
{

	function generate_calendar($cal_date, $day_name_length = 3, $first_day = 0, $_items, $class = "calendar_big", $offset = 0, $that)
	{
		
		$params = $that->tmpl_params['list'];
		$year = date('Y', $cal_date);
		$month = date('n', $cal_date);
		$month = $month + $offset;
		
		$first_of_month = gmmktime(0, 0, 0, $month, 1, $year);
		
		#remember that mktime will automatically correct if invalid dates are entered
		# for instance, mktime(0,0,0,12,32,1997) will be the date for Jan 1, 1998
		# this provides a built in "rounding" feature to generate_calendar()
		

		//	    $day_names = array(); #generate all the day names according to the current locale
		//	    for($n=0,$t=(3+$first_day)*86400; $n<7; $n++,$t+=86400) #January 4, 1970 was a Sunday
		//	        $day_names[$n] = (JString::ucfirst(gmstrftime('%A',$t))); #%A means full textual day name
		

		$day_names = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
		
		if (!$first_day)
		{
			// First day is Sunday
			array_pop($day_names);
			array_unshift($day_names, 'Sunday');
		}
		
		foreach($day_names as $k => $v)
			$day_names[$k] = JText::_($v);
		
		list($month, $year, $month_name, $weekday) = explode(',', gmstrftime('%m,%Y,%B,%w', $first_of_month));
		
		$month_name = cal_get_month_name($month);
		
		$weekday = ($weekday + 7 - $first_day) % 7; #adjust for $first_day
		$title = htmlentities(JString::ucfirst($month_name), ENT_QUOTES, 'UTF-8') . '&nbsp;' . $year; #note that some locales don't capitalize month and day names
		

		if ($class !== "calendar_big")
		{
			$onclick = 'document.adminForm.cal_view.value=3;document.adminForm.cal_date.value=' . mktime(0, 0, 0, $month, date('d', $cal_date), date('Y', $cal_date));
			$onclick .= ';document.adminForm.submit();';
			$title = "<a href=\"javascript:void(0);\" onclick=\"{$onclick}\">{$title}</a>";
		}
		$calendar = '<table class="' . $class . '" border="0" cellpading="0" cellspacing="0">' . "\n" . ($class !== "calendar_big" ? '<caption class="calendar-month">' . $title . '</caption>' : NULL) . "\n<tr class=\"sectiontableheader\" align=\"center\">";
		
		$row = 0;
		if ($day_name_length)
		{ #if the day names should be shown ($day_name_length > 0)
			#if day_name_length is >3, the full name of the day will be printed
			foreach($day_names as $d)
				$calendar .= '<th class="sectiontableheader" abbr="' . htmlentities($d, ENT_QUOTES, 'UTF-8') . '">' . htmlentities($day_name_length < 4 ? JString::substr($d, 0, $day_name_length) : $d, ENT_QUOTES, 'UTF-8') . '</th>';
			$calendar .= "</tr>\n<tr class=\"sectiontableentry" . ($row + 1) . "\">";
		}
		if ($weekday > 0)
			$calendar .= '<td colspan="' . $weekday . '">&nbsp;</td>'; #initial 'empty' days
		for($day = 1, $days_in_month = gmdate('t', $first_of_month); $day <= $days_in_month; $day ++, $weekday ++)
		{
			if ($weekday == 7)
			{
				$row = 1 - $row;
				$weekday = 0; #start a new week
				$calendar .= "</tr>\n<tr valign=\"middle\" class=\"sectiontableentry" . ($row + 1) . "\">";
			}
			
			$day_of_the_year = date('z', mktime(0, 0, 0, $month, $day, $year));
			$today = date('z', mktime(0, 0, 0, date('m'), date('d'), date('Y')));
			$day_title = $day;
			if (isset($_items[$year][$day_of_the_year]))
			{
				$date = new JDate(mktime(12, 0, 0, $month, $day, $year));				
				$c = 0;
				$hint = array();
				$item = $_items[$year][$day_of_the_year];
				$iparams = new JRegistry(@$item->params);
				if ($params->get('tmpl_core.client') == 'module')
				{
					//$link = 'index.php?option=com_resource&view=list&category_id='.$that->section->id.'&Itemid='.MEUrl::itemid(NULL, $that->section->id);
					//$link = MEUrl::linkAdditions($link);
					$link = Url::records($that->section->id);//MEUrl::link_list($that->section->id);
					$link .= '&cal_view=2&cal_date=' . $date->toUnix();
					$link = JRoute::_($link);
					$hint[] = '<p><button class="button" href="' . $link . '">' . JText::_('This week events') . '</button>';
				}
				else
				{
					$link = 'document.adminForm.cal_view.value=2; document.adminForm.cal_date.value=' . $date->toUnix() . '; document.adminForm.submit();';
					$hint[] = '<p><button class="button" href="javascript:void(0);" onclick="' . $link . '">' . JText::_('This week events') . '</button>';
				}
				
				if (count($_items[$year][$day_of_the_year]) > $params->get('tmpl_core.limit'))
				{
					if ($params->get('tmpl_core.client') == 'module')
					{
						//$link = 'index.php?option=com_resource&view=list&category_id='.$that->section->id.'&Itemid='.MEUrl::itemid(NULL, $that->section->id);
						//$link = MEUrl::linkAdditions($link);
						$link = Url::records($that->section->id);;
						$link = '&cal_view=1&cal_date=' . $date->toUnix();
						$link = JRoute::_($link);
						$hint[] = '<p><a class="button" href="' . $link . '">' . JText::_('This day events') . '</a></p>';
					}
					else
					{
						$link = 'document.adminForm.cal_view.value=1; document.adminForm.cal_date.value=' . $date->toUnix() . '; document.adminForm.submit();';
						$hint[] = '<p><button class="button" href="javascript:void(0);" onclick="' . $link . '">' . JText::_('This day events') . '</button>';
					}
				}
				
				$hint[] = '<div style="clear:both"></div>';
				$hint[] = '<ul class="calendar_hour_list">';
				foreach($_items[$year][$day_of_the_year] as $item)
				{
					$c ++;
					if ($c > $params->get('tmpl_core.limit'))
						break;
					$hint[] = cal_list_item($item, $that, FALSE, TRUE);
				
				}
				$hint[] = '</ul>';
				
				$body = str_replace(array("\n", "\r", "\t", 'class="hasTip"'), '', implode(" ", $hint));
				$body = str_replace('"', "'", $body);
				$body = preg_replace("/>\s+</iU", "> <", $body);
				
				$id = uniqid('calendar');
				
				//echo $date->format($iparams->get('item_time_format', '%A, %d %B %Y'));
				$tip = '<div id="MEcalendarTip' . $id . '" title="' . addslashes(htmlspecialchars($date->format($iparams->get('item_time_format', '%A, %d %B %Y')), ENT_QUOTES, 'UTF-8')) . '" style="display:none;">' . $body . '</div>';
				echo $tip;
				
				if ($params->get('tmpl_core.client') == 'module')
				{
					//$link = 'index.php?option=com_resource&view=list&category_id='.$that->section->id.'&Itemid='.MEUrl::itemid(NULL, $that->section->id);
					//$link = MEUrl::linkAdditions($link);
					$link = Url::records($that->section->id);;
					$link .= '&cal_view=1&cal_date=' . $date->toUnix();
					$link = JRoute::_($link);
					$day_title = "<a class=\"hasTip2\" href=\"{$link}\" rel=\"$id\">{$day}</a>";
				
		//$day_title = "<a class=\"hasTip\" href=\"javascript:void(0);\" {$tip}>{$day}</a>";			
				}
				else
				{
					$onclick = 'document.adminForm.cal_view.value=1;document.adminForm.cal_date.value=' . mktime(0, 0, 0, $month, $day, date('Y', $cal_date));
					$onclick .= ';document.adminForm.submit();';
					$day_title = "<a class=\"hasTip2\" href=\"javascript:void(0);\" onclick=\"{$onclick}\" rel=\"$id\">{$day}</a>";
				}
				
			}
			$calendar .= "<td align=\"center\"" . (isset($_items[$year][$day_of_the_year]) ? ' class="calendar_has_items" ' : NULL) . ($day_of_the_year == $today ? ' id="calendar_today" ' : NULL) . ">{$day_title}</td>";
		}
		if ($weekday != 7)
			$calendar .= '<td colspan="' . (7 - $weekday) . '">&nbsp;</td>'; #remaining "empty" days
		

		return $calendar . "</tr>\n</table>\n";
	}
}
if (!function_exists('cal_display_title'))
{

	function cal_display_title($title, $client)
	{
		$open = '<h2 class="contentheading">';
		$close = '</h2>';
		if ($client == 'module')
		{
			$open = '<h3>';
			$close = '</h3>';
		}
		return $open . $title . $close;
	}
}

if (!function_exists('cal_get_month_name'))
{

	function cal_get_month_name($month)
	{
		static $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'Novermber', 'December');
		
		return JText::_($months[intval($month) - 1]);
	}
}

?>
<?php

$document = JFactory::getDocument();
$document->addScriptDeclaration('window.addEvent("domready", function() {
				
				var JTooltips = new Tips($$(".hasTip2"), { maxTitleChars: 50, hideDelay: ' . (int)$params->get('tmpl_core.hint_delay', 1500) . ', 
				className: "mytips", fixed: true, offsets: {x: 23, y: 23}});
			
				JTooltips.addEvent("onHide", function( tip ) {
					if (tip.forceVisible)
						JTooltips.show();
				});
				
				$$(".hasTip2").each(function( e ) {
					
					if ($("MEcalendarTip" + e.rel))
					{
						e.store("tip:title", $("MEcalendarTip" + e.rel).getAttribute("title"));
						e.store("tip:text", $("MEcalendarTip" + e.rel).innerHTML);
						
						JTooltips.addEvent("onShow", function(tip) {
							tip.addEvent("mouseenter", function() {
							
								tip.myText = $("MEcalendarTip" + e.rel).innerHTML;
								tip.forceVisible = true;
							});
							
							tip.addEvent("mouseleave", function() {
								tip.forceVisible = false;
							});
							
						});
					}
				});
			
		});
		');
$document->addStyleSheet(JURI::root(TRUE) . '/components/com_cobalt/views/records/tmpl/default_list_calendar.css');
?>

<?php
global $app;
$first_day = $params->get('tmpl_core.first_day');
$option = JRequest::getVar('option', 'com_cobalt');
$catid = JRequest::getInt('category_id', $this->category->id);
$Itemid = JRequest::getInt('Itemid');

$_items = array();
foreach($this->items as $item)
	$_items[date('Y', $item->ctime->toUnix())][date('z', $item->ctime->toUnix())][] = $item;

switch ($params->get('tmpl_core.filters_mode', 1))
{
	case 3 :
	case 1 :
		$view_name = "{$option}.calendar.cal_view.{$Itemid}.{$catid}";
		$date_name = "{$option}.calendar.date_view.{$Itemid}.{$catid}";
		break;
	case 2 :
		$view_name = "{$option}.calendar.cal_view.{$Itemid}";
		$date_name = "{$option}.calendar.date_view.{$Itemid}";
		break;
}
$cur_view = $app->getUserStateFromRequest($view_name, 'cal_view', $params->get('tmpl_core.view', 3), 'int');
//echo $cur_view;
if (JRequest::getInt('cal_date'))
{
	$cal_date = JRequest::getInt('cal_date', time());
	$app->setUserState($date_name, $cal_date);
}
else
	$cal_date = $app->getUserState($date_name);

if (!$cal_date || $cal_date == 1)
	$cal_date = time();

switch ($cur_view)
{
	case 1 :
		$click_prev = 'document.adminForm.cal_date.value=' . mktime(0, 0, 0, date('n', $cal_date), date('d', $cal_date) - 1, date('Y', $cal_date));
		$click_prev .= ';document.adminForm.submit();';
		$click_next = 'document.adminForm.cal_date.value=' . mktime(0, 0, 0, date('n', $cal_date), date('d', $cal_date) + 1, date('Y', $cal_date));
		$click_next .= ';document.adminForm.submit();';
		break;
	case 2 :
		$click_prev = 'document.adminForm.cal_date.value=' . mktime(0, 0, 0, date('n', $cal_date), date('d', $cal_date) - 7, date('Y', $cal_date));
		$click_prev .= ';document.adminForm.submit();';
		$click_next = 'document.adminForm.cal_date.value=' . mktime(0, 0, 0, date('n', $cal_date), date('d', $cal_date) + 7, date('Y', $cal_date));
		$click_next .= ';document.adminForm.submit();';
		break;
	case 3 :
		$click_prev = 'document.adminForm.cal_date.value=' . mktime(0, 0, 0, date('n', $cal_date) - 1, date('d', $cal_date), date('Y', $cal_date));
		$click_prev .= ';document.adminForm.submit();';
		$click_next = 'document.adminForm.cal_date.value=' . mktime(0, 0, 0, date('n', $cal_date) + 1, date('d', $cal_date), date('Y', $cal_date));
		$click_next .= ';document.adminForm.submit();';
		break;
	case 4 :
		$click_prev = 'document.adminForm.cal_date.value=' . mktime(0, 0, 0, date('n', $cal_date) - 2, date('d', $cal_date), date('Y', $cal_date));
		$click_prev .= ';document.adminForm.submit();';
		$click_next = 'document.adminForm.cal_date.value=' . mktime(0, 0, 0, date('n', $cal_date) + 2, date('d', $cal_date), date('Y', $cal_date));
		$click_next .= ';document.adminForm.submit();';
		break;
	case 5 :
		$click_prev = 'document.adminForm.cal_date.value=' . mktime(0, 0, 0, date('n', $cal_date) - 4, date('d', $cal_date), date('Y', $cal_date));
		$click_prev .= ';document.adminForm.submit();';
		$click_next = 'document.adminForm.cal_date.value=' . mktime(0, 0, 0, date('n', $cal_date) + 4, date('d', $cal_date), date('Y', $cal_date));
		$click_next .= ';document.adminForm.submit();';
		break;
	case 6 :
		$click_prev = 'document.adminForm.cal_date.value=' . mktime(0, 0, 0, date('n', $cal_date) - 6, date('d', $cal_date), date('Y', $cal_date));
		$click_prev .= ';document.adminForm.submit();';
		$click_next = 'document.adminForm.cal_date.value=' . mktime(0, 0, 0, date('n', $cal_date) + 6, date('d', $cal_date), date('Y', $cal_date));
		$click_next .= ';document.adminForm.submit();';
		break;
	case 7 :
		$click_prev = 'document.adminForm.cal_date.value=' . mktime(0, 0, 0, date('n', $cal_date) - 3, date('d', $cal_date), date('Y', $cal_date));
		$click_prev .= ';document.adminForm.submit();';
		$click_next = 'document.adminForm.cal_date.value=' . mktime(0, 0, 0, date('n', $cal_date) + 3, date('d', $cal_date), date('Y', $cal_date));
		$click_next .= ';document.adminForm.submit();';
		break;
}
?>
<br />
<table cellpadding="5" cellspacing="0" border="0" width="100%">
	<?php if ($params->get('tmpl_core.client') != 'module') : ?>
	<tr>
		<td align="left"><img onclick="<?php echo $click_prev?>" style="cursor: pointer" src="<?php echo JURI::root(TRUE);?>/components/com_cobalt/images/back.png" /></td>
		<td align="center">
			<?php if ($params->get('tmpl_core.allow_view') && in_array('day', $params->get('tmpl_core.allow_view'))) : ?>
			<button class="button" href="javascript:void(0);" onclick="document.adminForm.cal_view.value=1; document.adminForm.submit();"><?php echo JText::_('Day') ?></button>
			<?php endif; ?>  
			<?php if ($params->get('tmpl_core.allow_view') && in_array('week', $params->get('tmpl_core.allow_view')))  : ?>
			<button class="button" href="javascript:void(0);" onclick="document.adminForm.cal_view.value=2; document.adminForm.submit();"><?php echo JText::_('Week') ?></button>
			<?php endif; ?> 
			<?php if ($params->get('tmpl_core.allow_view') && in_array('month', $params->get('tmpl_core.allow_view')))  : ?>
			<button class="button" href="javascript:void(0);" onclick="document.adminForm.cal_view.value=3; document.adminForm.submit();"><?php echo JText::_('Month') ?></button>
			<?php endif; ?>
			<?php if ($params->get('tmpl_core.allow_view') && in_array('month2', $params->get('tmpl_core.allow_view')))  : ?>
			<button class="button" href="javascript:void(0);" onclick="document.adminForm.cal_view.value=4; document.adminForm.submit();"><?php echo JText::_('2 Months') ?></button>
			<?php endif; ?>
			<?php if ($params->get('tmpl_core.allow_view') && in_array('month3', $params->get('tmpl_core.allow_view')))  : ?> 
			<button class="button" href="javascript:void(0);" onclick="document.adminForm.cal_view.value=7; document.adminForm.submit();"><?php echo JText::_('3 Months') ?></button>
			<?php endif; ?>
			<?php if ($params->get('tmpl_core.allow_view') && in_array('month4', $params->get('tmpl_core.allow_view')))  : ?> 
			<button class="button" href="javascript:void(0);" onclick="document.adminForm.cal_view.value=5; document.adminForm.submit();"><?php echo JText::_('4 Months') ?></button>
			<?php endif; ?>
			<?php if ($params->get('tmpl_core.allow_view') && in_array('month6', $params->get('tmpl_core.allow_view')))  : ?>
			<button class="button" href="javascript:void(0);" onclick="document.adminForm.cal_view.value=6; document.adminForm.submit();"><?php echo JText::_('6 Months') ?></button>
			<?php endif; ?> 		
		</td>
		<td align="right"><img onclick="<?php echo $click_next?>" style="cursor: pointer" src="<?php echo JURI::root(TRUE);?>/components/com_cobalt/images/next.png" /></td>
	</tr>
	<?php endif; ?>
	<tr>
		<td colspan="3" id="cal_records_list">
<?php

$theDate = JFactory::getDate($cal_date);

switch ($cur_view)
{
	case 1 :
		foreach($this->items as $item)
			$_items[$item->ctime->format('%Y%m%d')][$item->ctime->format('%H')][] = $item;
				
	//$title = $theDate->format('%A').', '.$theDate->format('%e').' '.$theDate->format('%b %Y');
		$title = date('l, jS \of F Y', $cal_date);
		$sort_date = date('Ymd', $cal_date);
		echo cal_display_title($title, $params->get('tmpl_core.client'));
		?>
		<table width="100%" border="0" cellpadding="0" cellspacing="0"	summary="<?php 	echo JText::_('Day Calendar')?>">
			<tr class="sectiontableheader">
				<th class="sectiontableheader" width="90px"><?php echo JText::_('Hour')?></th>
				<th class="sectiontableheader"><?php echo JText::_('Events')?></th>
			</tr>
		  <?php
		$row = 0;
		
		
			for($h = 0; $h <= 23; $h ++):
				if($h<10) $h = "0$h";
				?>
				  <tr class="sectiontableentry<?php	echo $row + 1;	?>" valign="top">
					<td class="calendar_hour"><?php	printf('%0.1d', $h)?>:00</td>
					<td>
					<?php
					if (@$_items[$sort_date][$h])
					{
						echo '<ul class="calendar_hour_list">';
						foreach($_items[$sort_date][$h] as $item)
						{
							$item->ntitle = str_replace('<a ', '<a rel="' . $item->id . '"', $item->title);
							$item->ntitle = str_replace('class="contentpagetitle"', 'class="contentpagetitle hasTip2"', $item->ntitle);
							
							echo cal_event_body($item, $this);
							echo cal_list_item($item, $this);
						}
						echo '</ul>';
					}
					?>
					</td>
				</tr>
			  <?php
				$row = 1 - $row;
			endfor;
		
		?>
		</table>
		<?php
		break;
	case 2 :
		$first_day ? $week = '%W' : $week = '%U';
		foreach($this->items as $item)
		{
			$_items[$item->ctime->format('%A')][] = $item;
		}
		$day_of_week = strftime('%w', $cal_date);
		if ($first_day == 1)
			$day_of_week --;
		$start_day = mktime(0, 0, 0, date('n', $cal_date), (date('d', $cal_date) - $day_of_week), date('Y', $cal_date));
		
		$start_day_date = JFactory::getDate($start_day);
		?>
		<?php
		
		// date('Y, F', $start_day);
		echo cal_display_title(strftime($week, $start_day) . ' ' . JText::_('Week of the year') . ' ' . $start_day_date->format('%Y, %B'), $params->get('tmpl_core.client'));
		
		?>
		<table width="100%" border="0" cellpadding="0" cellspacing="0" 	summary="<?php echo JText::_('Day Calendar')?>">
			<tr class="sectiontableheader">
				<th class="sectiontableheader" width="50px"><?php echo JText::_('Day')?></th>
				<th class="sectiontableheader"><?php echo JText::_('Events')?></th>
			</tr>
		  <?php
		$row = 0;
		for($w = 0; $w < 7; $w ++) : ?>
			<?php
			$date = new JDate((int)mktime(0, 0, 0, date('n', $start_day), (date('d', $start_day) + $w), date('Y', $start_day)));
			?>
			  <tr class="sectiontableentry<?php	echo $row + 1;?>" valign="top">
				<td class="calendar_hour"><?php	echo $date->format('%A');?><br />
				<span class="small" style="font-size: 11px;"><?php	echo $date->format('%d %B');?></span>
				</td>
				<td>
				<?php if (@$_items[$date->format('%A')])
				{
					$c = 0;
					echo '<ul class="calendar_hour_list">';
					foreach($_items[$date->format('%A')] as $item)
					{
						$c ++;
						if ($c > $params->get('tmpl_core.limit'))
							break;
						
						$item->ntitle = str_replace('<a ', '<a rel="' . $item->id . '"', $item->title);
						$item->ntitle = str_replace('class="contentpagetitle"', 'class="contentpagetitle hasTip2"', $item->ntitle);
						
						echo cal_event_body($item, $this);
						echo cal_list_item($item, $this);
					}
					echo '</ul>';
					if (count($_items[$date->format('%A')]) > $params->get('tmpl_core.limit'))
					{
						$link = 'document.adminForm.cal_view.value=1; document.adminForm.cal_date.value=' . $date->toUnix() . '; document.adminForm.submit();';
						echo '<p><button class="button" href="javascript:void(0);" onclick="' . $link . '">' . JText::_('See all day events') . '</button>';
					}
				}
				?>
				</td>
			</tr>
		  <?php	$row = 1 - $row;
		endfor;
		?>
		</table>
		
		<?php
		break;
	case 3 :
		$month_name = cal_get_month_name(date('n', $cal_date));
		echo cal_display_title(date('Y', $cal_date) . ', ' . $month_name, $params->get('tmpl_core.client'));
		echo generate_calendar($cal_date, 15, (int)$first_day, $_items, "calendar_big", 0, $this);
		break;
	case 4 :
		?>
<table cellpadding="2" cellspacing="2" border="0">
			<tr valign="top">
				<td><?php
		echo generate_calendar($cal_date, 3, (int)$first_day, $_items, 'calendar_middle', 0, $this);
		?></td>
				<td><?php
		echo generate_calendar($cal_date, 3, (int)$first_day, $_items, 'calendar_middle', 1, $this);
		?></td>
			</tr>
		</table>

		<?php
		break;
	case 7 :
		?>
<table cellpadding="2" cellspacing="2" border="0">
			<tr valign="top">
				<td><?php
		echo generate_calendar($cal_date, 1, (int)$first_day, $_items, 'calendar_small', 0, $this);
		?></td>
				<td><?php
		echo generate_calendar($cal_date, 1, (int)$first_day, $_items, 'calendar_small', 1, $this);
		?></td>
				<td><?php
		echo generate_calendar($cal_date, 1, (int)$first_day, $_items, 'calendar_small', 2, $this);
		?></td>
			</tr>
		</table>

		<?php
		break;
	case 5 :
		?>
<table cellpadding="2" cellspacing="2" border="0">
			<tr valign="top" align="center">
				<td><?php
		echo generate_calendar($cal_date, 3, (int)$first_day, $_items, 'calendar_middle', 0, $this);
		?></td>
				<td><?php
		echo generate_calendar($cal_date, 3, (int)$first_day, $_items, 'calendar_middle', 1, $this);
		?></td>
			</tr>
			<tr valign="top" align="center">
				<td><?php
		echo generate_calendar($cal_date, 3, (int)$first_day, $_items, 'calendar_middle', 2, $this);
		?></td>
				<td><?php
		echo generate_calendar($cal_date, 3, (int)$first_day, $_items, 'calendar_middle', 3, $this);
		?></td>
			</tr>
		</table>
		
		<?php
		break;
	case 6 :
		?>
<table cellpadding="2" cellspacing="2" border="0">
			<tr valign="top">
				<td><?php
		echo generate_calendar($cal_date, 1, (int)$first_day, $_items, 'calendar_small', 0, $this);
		?></td>
				<td><?php
		echo generate_calendar($cal_date, 1, (int)$first_day, $_items, 'calendar_small', 1, $this);
		?></td>
				<td><?php
		echo generate_calendar($cal_date, 1, (int)$first_day, $_items, 'calendar_small', 2, $this);
		?></td>
			</tr>
			<tr valign="top">
				<td><?php
		echo generate_calendar($cal_date, 1, (int)$first_day, $_items, 'calendar_small', 3, $this);
		?></td>
				<td><?php
		echo generate_calendar($cal_date, 1, (int)$first_day, $_items, 'calendar_small', 4, $this);
		?></td>
				<td><?php
		echo generate_calendar($cal_date, 1, (int)$first_day, $_items, 'calendar_small', 5, $this);
		?></td>
			</tr>
		</table>
		
		<?php
		break;
}

?>
		</td>
	</tr>
</table>
<script type="text/javascript"><!--
	$$(".menu-container").each(function(el){
		el.getElements('ul').each(function(mnu){
			mnu.addClass("horizontal");
			mnu.MooDropMenu({
				onOpen: function(el){
					el.fade('in')
				},
				onClose: function(el){
					el.fade('out');
				},
				onInitialize: function(el){
					el.fade('hide').set('tween', {duration:500});
				}
			});
		});
	});
	
--></script>

<input type="hidden" name="cal_view" value="<?php echo JRequest::getInt('cal_view', $cur_view); ?>" />
<input type="hidden" name="cal_date" value="<?php echo JRequest::getInt('cal_date', $cal_date); ?>" />
<?php return; ?>

<?php if ($params->get('tmpl_params.template')):?>
	<?php echo $this->loadTemplate('list_'.$params->get('tmpl_params.template'));?>
<?php endif;?>
