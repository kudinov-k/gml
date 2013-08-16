<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die();

$class[] = $this->params->get('core.field_class', 'inputbox');
$required = NULL;

if ($this->required)
{
	$class[] = 'required';
	$required = ' required="true" ';
}

$class = ' class="' . implode(' ', $class) . '"';

settype($this->value, 'array');

if (!isset($this->value['rent']))
{
	$this->value['rent'] = isset($this->value[0]) ? $this->value[0] : 1;
}
if (!isset($this->value['sale']))
{
	$this->value['sale'] = 0;
}
?>

<?php echo JText::_('CRENT');?>
<input type="text" name="jform[fields][<?php echo $this->id;?>][rent]" id="field_<?php echo $this->id;?>" value="<?php echo $this->value['rent'];?>"
	<?php echo $class .$required;?>>
	<br /><br />
<?php echo JText::_('CSALE');?>
<input type="text" name="jform[fields][<?php echo $this->id;?>][sale]" id="field_<?php echo $this->id;?>" value="<?php echo $this->value['sale'];?>"
	<?php echo $class .$required;?>>

