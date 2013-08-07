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

if (!isset($this->value['amount']))
{
	$this->value['amount'] = $this->value ? $this->value : 1;
}
if (!isset($this->value['book_type']))
{
	$this->value['book_type'] = 0;
}
var_dump($this->value);
?>


<input type="text" name="jform[fields][<?php echo $this->id;?>][amount]" id="field_<?php echo $this->id;?>" value="<?php echo $this->value['amount'];?>"
	<?php echo $class .$required;?>>

	<br /><br />

<select name="jform[fields][<?php echo $this->id;?>][book_type]" id="field_<?php echo $this->id;?>_type">
	<option value="0">CRENT</option>
	<option value="1" <?php if($this->value['book_type']): ?> selected="selected"<?php endif;?>>CSALE</option>
</select>
