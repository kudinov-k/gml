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
if (!isset($this->value['order']))
{
	$this->value['order'] = 0;
}
?>

<table>
	<thead>
		<tr>
			<th>Операция</th>
			<th>Баз.ед.изм</th>
			<th>Время(ч)</th>
			<th>Цена</th>
			<th>Ед.время</th>
			<th>Скидка</th>
			<th>Налог</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>Аренда</td>
			<td>
				<select name="jform[fields][<?php echo $this->id;?>][rent][unit]" style="width:70px;">
					<?php
					$units = explode("\n", $this->params->get('params.unit', ''));
					foreach ($units as $key => $unit):?>
						<option
							<?php  if(isset($this->value['rent']['unit']) && $this->value['rent']['unit'] == $key):?>
								selected="selected"
							<?php endif;?>

							value="<?php echo $key;?>"><?php echo $unit;?>
						</option>
					<?php endforeach;?>
				</select>
			</td>
			<td>
				<input type="checkbox" />
			</td>
			<td>
				<select name="jform[fields][<?php echo $this->id;?>][rent][time]" style="width:70px;">
					<?php
					$times = explode("\n", $this->params->get('params.times', ''));
					foreach ($times as $key => $time):?>
						<option
							<?php  if(isset($this->value['rent']['unit']) && $this->value['rent']['time'] == $key):?>
								selected="selected"
							<?php endif;?>

							value="<?php echo $key;?>"><?php echo $time;?>
						</option>
					<?php endforeach;?>
				</select>
			</td>
		</tr>
	</tbody>
</table>

<?php echo JText::_('CRENT');?>
<input type="text" name="jform[fields][<?php echo $this->id;?>][rent]" id="field_<?php echo $this->id;?>" value="<?php echo $this->value['rent'];?>"
	<?php echo $class .$required;?>>
	<br /><br />
<?php echo JText::_('CSALE');?>
<input type="text" name="jform[fields][<?php echo $this->id;?>][sale]" id="field_<?php echo $this->id;?>" value="<?php echo $this->value['sale'];?>"
	<?php echo $class .$required;?>>
	<br /><br />
<?php echo JText::_('CORDER');?>
<input type="text" name="jform[fields][<?php echo $this->id;?>][order]" id="field_<?php echo $this->id;?>" value="<?php echo $this->value['order'];?>"
	<?php echo $class .$required;?>>

