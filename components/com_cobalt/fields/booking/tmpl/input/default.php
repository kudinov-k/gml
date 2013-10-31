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
			<th>Время</th>
			<th>Цена</th>
			<th>Фикс.</th>
			<th>Без налогов</th>
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
			<td>день</td>
			<td>
				<?php
				$rent_price = isset($this->value['rent']['price']) ? $this->value['rent']['price'] : 0;
				$fix = isset($this->value['rent']['fix']) ? 'checked="checked"' : null;
				$_tax = isset($this->value['rent']['tax']) ? 'checked="checked"' : null;
				?>
				<input type="text" value="<?php echo $rent_price;?>" class="input-mini" name="jform[fields][<?php echo $this->id;?>][rent][price]" />
			</td>
			<td><input type="checkbox" name="jform[fields][<?php echo $this->id;?>][rent][fix]" <?php echo $fix;?> /></td>
			<td><input type="checkbox" name="jform[fields][<?php echo $this->id;?>][rent][tax]" <?php echo $_tax;?> /></td>
			<td>
				<select name="jform[fields][<?php echo $this->id;?>][tax][]" multiple="multiple" style="width:150px;">
					<?php
					$taxs = explode("\n", $this->params->get('params.tax', ''));
					$v_taxes = $this->value['tax'];
					settype($v_taxes, 'array');
					foreach ($taxs as $key => $tax):?>
						<option
							<?php  if(isset($this->value['tax']) && in_array($key, $v_taxes)):?>
								selected="selected"
							<?php endif;?>

							value="<?php echo $key;?>"><?php echo $tax;?>
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
			<td>&nbsp;</td>
			<td>
				<?php
					$v = null;
					if(isset($this->value['sale']['price']))
					{
						$v = $this->value['sale']['price'];
					}
					$fix = isset($this->value['sale']['fix']) ? 'checked="checked"' : null;
					$_tax = isset($this->value['sale']['tax']) ? 'checked="checked"' : null;
				?>
				<input type="text" value="<?php echo $v;?>" class="input-mini" name="jform[fields][<?php echo $this->id;?>][sale][price]" />
			</td>
			<td><input type="checkbox" name="jform[fields][<?php echo $this->id;?>][sale][fix]" <?php echo $fix;?> /></td>
			<td><input type="checkbox" name="jform[fields][<?php echo $this->id;?>][sale][tax]" <?php echo $_tax;?> /></td>
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
			<td>час</td>
			<td>
				<?php
				$order_price = isset($this->value['order']) ? $this->value['order']['price'] : 0;
				$fix = isset($this->value['order']['fix']) ? 'checked="checked"' : null;
				$_tax = isset($this->value['order']['tax']) ? 'checked="checked"' : null;
				?>
				<input type="text" value="<?php echo $order_price;?>" class="input-mini" name="jform[fields][<?php echo $this->id;?>][order][price]" />
			</td>
			<td><input type="checkbox" name="jform[fields][<?php echo $this->id;?>][order][fix]" <?php echo $fix;?> /></td>
			<td><input type="checkbox" name="jform[fields][<?php echo $this->id;?>][order][tax]" <?php echo $_tax;?> /></td>

		</tr>
	</tbody>
</table>

