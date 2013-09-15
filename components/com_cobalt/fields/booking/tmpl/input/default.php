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

//var_dump($this->value);
// settype($this->value, 'array');

// if (!isset($this->value['rent']))
// {
// 	$this->value['rent'] = isset($this->value[0]) ? $this->value[0] : 1;
// }
// if (!isset($this->value['sale']))
// {
// 	$this->value['sale'] = 0;
// }
// if (!isset($this->value['order']))
// {
// 	$this->value['order'] = 0;
// }
?>

<table class="table">
	<thead>
		<tr>
			<th>Операция</th>
			<th>Баз.ед.изм</th>
			<th>Время(ч)</th>
			<th>Цена</th>
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
				<table class="table">
				<?php
				$times = explode("\n", $this->params->get('params.times_d', ''));
				foreach ($times as $key => $time):
					$v = null;
					if(isset($this->value['rent']['price'][$key]))
					{
						$v = $this->value['rent']['price'][$key];
					}
				?>
					<tr>
					<td><?php echo $time;?></td>
					<td><input type="text" value="<?php echo $v;?>" class="input-mini" name="jform[fields][<?php echo $this->id;?>][rent][price][<?php echo $key;?>]" /></td>
					</tr>
				<?php endforeach;?>
				</table>
			</td>
			<td>
				<select name="jform[fields][<?php echo $this->id;?>][tax][]" multiple="multiplr" style="width:150px;">
					<?php
					$units = explode("\n", $this->params->get('params.tax', ''));
					foreach ($units as $key => $unit):?>
						<option
							<?php  if(isset($this->value['tax']) && in_array($key, $this->value['tax'])):?>
								selected="selected"
							<?php endif;?>

							value="<?php echo $key;?>"><?php echo $unit;?>
						</option>
					<?php endforeach;?>
				</select>
			</td>
		</tr>

		<tr>
			<td>Продажа</td>
			<td>
				<select name="jform[fields][<?php echo $this->id;?>][sale][unit]" style="width:70px;">
					<?php
					$units = explode("\n", $this->params->get('params.unit', ''));
					foreach ($units as $key => $unit):?>
						<option
							<?php  if(isset($this->value['sale']['unit']) && $this->value['sale']['unit'] == $key):?>
								selected="selected"
							<?php endif;?>

							value="<?php echo $key;?>"><?php echo $unit;?>
						</option>
					<?php endforeach;?>
				</select>
			</td>
			<td>

			</td>
			<td>
				<?php
					$v = null;
					if(isset($this->value['sale']['price']))
					{
						$v = $this->value['sale']['price'];
					}
				?>
				<input type="text" value="<?php echo $v;?>" class="input-mini" name="jform[fields][<?php echo $this->id;?>][sale][price]" />
			</td>
		</tr>

		<tr>
			<td>Заказ</td>
			<td>
				<select name="jform[fields][<?php echo $this->id;?>][order][unit]" style="width:70px;">
					<?php
					$units = explode("\n", $this->params->get('params.unit', ''));
					foreach ($units as $key => $unit):?>
						<option
							<?php  if(isset($this->value['order']['unit']) && $this->value['order']['unit'] == $key):?>
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
				<table>
				<?php
				$times = explode("\n", $this->params->get('params.times_h', ''));
				foreach ($times as $key => $time):
					$v = null;
					if(isset($this->value['order']['price'][$key]))
					{
						$v = $this->value['order']['price'][$key];
					}
				?>
					<tr>
					<td><?php echo $time;?></td>
					<td><input type="text" value="<?php echo $v;?>" class="input-mini" name="jform[fields][<?php echo $this->id;?>][order][price][<?php echo $key;?>]" /></td>
					</tr>
				<?php endforeach;?>
				</table>
			</td>

		</tr>
	</tbody>
</table>

