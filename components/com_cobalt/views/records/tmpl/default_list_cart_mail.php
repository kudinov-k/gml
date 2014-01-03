<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

$app = JFactory::getApplication();
$this->cart = $app->getUserState('booking_cart', array());

$db = JFactory::getDbo();
$query = $db->getQuery(true);
$this->mod_params = new JRegistry($app->input->getString('mod_params', ''));

$this->rmodel = JModelLegacy::getInstance('Fields', 'CobaltModel');

	$cats_model = JModelLegacy::getInstance('Categories', 'CobaltModel');
	$sorted = $cat_ids = $showed_parents = array();
	foreach ($this->items AS $item)
	{
		$cat_id = array_shift(array_keys($item->categories));
		$cats[$cat_id] = @$item->categories[$cat_id];
		$sorted[$cat_id][] = $item;
	}
	ArrayHelper::clean_r($cats);
	$cats[0] = 0;
	$query->select('c.*');
	$query->from('#__js_res_categories AS c');
	$query->where('c.id IN (' . implode(',', array_keys($cats)) . ')');
	$query->order('c.lft');
	$db->setQuery($query);
	$sorted_cats = $db->loadColumn();
?>

<style>
.cart_cat{
	border:1px solid #ccc;
	border-radius: 5px;
	padding: 5px;
	margin-bottom: 10px;
}
</style>

	<?php $this->total = 0;?>
	<?php foreach ($sorted_cats AS $cat_id):?>
	<div class="cart_cat">
		<?php
		$parents = $cats_model->getParentsObjectsByChild($cat_id);
		$f_cat = 0;
		foreach ($parents as $parent)
		{
			if(in_array($parent->id, $showed_parents)) {$f_cat++; continue;}
			?>
			<div class="<?php echo $prefix;?><?php if(!$f_cat):?>category<?php $f_cat++; else:?>subcategory<?php endif;?>"><?php echo $parent->title;?></div>
			<div class="clearfix"></div>
			<?php
			$showed_parents[] = $parent->id;
		}
		?>
		<table class="table">
			<thead>
				<tr>
					<th width="15%"></th>
					<th>Операция</th>
					<th>Цена</th>
					<th>Ед.изм</th>
					<th>Кол-во</th>
					<th>Период</th>
					<th>Сумма</th>
				</tr>
			</thead>
			<tbody>
				<?php $this->total_cat = 0;?>
				<?php $this->tax_cat = 0;?>
				<?php foreach ($sorted[$cat_id] AS $item):?>
					<?php
						$this->book_field = $this->rmodel->getField($this->mod_params->get('booking_id'), $item->type_id, $item->id);
						$this->book_field->units = explode("\n", $this->book_field->params->get('params.unit'));
					?>
					<?php echo getItemBlock($item, $this); ?>

				<?php endforeach;?>
				<tr>
					<td colspan="7">
						<table class="pull-right">
							<tr>
								<td>Всего по разделу</td>
	<td><?php echo $this->book_field->nformat(round($this->total_cat, -1));?>
								<?php echo JText::_($this->book_field->params->get('params.cur_output'));?></td>
							</tr>

							<?php if(empty($this->cart['unf'])):?>
								<?php echo getTax($this->total_cat, $this->book_field, $this);?>
							<?php endif;?>

							<tr>
								<td>Итого по разделу</td>
								<td><?php echo $this->book_field->nformat(round($this->total_cat + $this->tax_cat, -1));?>
								<?php echo JText::_($this->book_field->params->get('params.cur_output'))?></td>
							</tr>
						</table>
					</td>
				</tr>

			</tbody>
		</table>
	</div>
	<?php endforeach;?>
	<div class="pull-right">
		<table class="table">
<tr>
				<td>Имею УНФ:</td>
				<?php
					$checked = !empty($this->cart['unf']) ? 'Да' : 'Нет';
				?>
				<td><?php echo $checked;?></td>
			</tr>
			<tr>
				<td>Количество дней:</td>
				<td><?php echo !empty($this->cart['total_days']) ? $this->cart['total_days'] : 1;?></td>
			</tr>
		</table>
	</div>
	<div class="clearfix"></div>
	<div class="pull-right">
		<table class="table">
			<tr>
				<td>Всего</td>
				<td><?php echo $this->book_field->nformat(round($this->total, -1));?>
				<?php echo JText::_($this->book_field->params->get('params.cur_output'));?></td>
			</tr>

			<?php if(empty($this->cart['unf'])):?>
				<?php $this->tax_cat = 0;?>
				<?php echo getTax($this->total, $this->book_field, $this);?>
			<?php endif;?>

			<tr>
				<td>Итого</td>
				<td><?php echo $this->book_field->nformat(round($this->total + $this->tax_cat, -1)); ?>
				<?php echo JText::_($this->book_field->params->get('params.cur_output'));?></td>
			</tr>
		</table>
	</div>

<?php

function getTax($price, $field, $that)
{
	$tax = $field->params->get('params.tax');
	$tax = explode("\n", $tax);

	$formulas = $field->params->get('params.formula');
	$formulas = explode("\n", $formulas);
	if (empty($formulas) || empty($tax))
	{
		return;
	}

	ob_start();
	?>
	<?php foreach ($formulas as $key => $formula):?>
	<?php if (!isset($tax[$key])) continue;?>
	<tr>
		<td><?php echo $tax[$key];?></td>
		<td>
			<?php eval('$_tax = '.str_replace("[PRICE]", $price, $formula).';')?>
			<?php
				$that->tax_cat += $_tax;
			?>
			<?php echo $that->book_field->nformat(round($_tax, -1));?>
			<?php echo JText::_($that->book_field->params->get('params.cur_output'));?>
		</td>
	</tr>
	<?php endforeach;?>
	<?php
	return ob_get_clean();
}


function getItemBlock($item, $that, $core_fields = '')
{
	ob_start();
	$rowspan = 0;
	if(isset($that->cart['rent'][$item->id]))
		$rowspan += 1;
	if(isset($that->cart['sale'][$item->id]))
		$rowspan += 1;
	if(isset($that->cart['order'][$item->id]))
		$rowspan += 1;
	$title_show = false;
?>

		<?php if(isset($that->cart['rent'][$item->id])): ?>
		<tr class="item-block">
			<?php if(!$title_show):?>
			<td rowspan="<?php echo $rowspan;?>"><?php getTitle($item, $that);?></td>
			<?php $title_show = true; endif;?>
			<td><?php echo JText::_('CRENT');?></td>
			<td>
				<?php
					if(!isset($that->book_field->value['rent']['fix'])){
						$price = $that->book_field->getReadyPrice($that->book_field->value['rent'], true);
					} else {
						$price = @$that->book_field->value['rent']['price'];
					}
					echo $that->book_field->nformat($price);
				?>

			<?php echo JText::_($that->book_field->params->get('params.cur_output'));?></td>
			<td>сут.</td>
			<td><?php echo $that->cart['rent'][$item->id]?>

				<?php
					echo isset($that->book_field->units[$that->book_field->value['rent']['unit']]) ? $that->book_field->units[$that->book_field->value['rent']['unit']] : 'No unit';
				?>
			</td>
			<td><?php
				$time = isset($that->cart['time_rent'][$item->id]) ? $that->cart['time_rent'][$item->id] : 1;
				echo $time;

				?> сут.</td>
			<td>
				<?php
					$a = $that->cart['rent'][$item->id] * $price * $time;
					$that->total_cat += $a;
					$that->total += $a;
					echo $that->book_field->nformat($a);
				?>
				<?php echo JText::_($that->book_field->params->get('params.cur_output'));?>
			</td>
		</tr>
		<?php endif;?>


		<?php if(isset($that->cart['sale'][$item->id])): ?>
		<tr class="item-block">
			<?php if(!$title_show):?>
			<td rowspan="<?php echo $rowspan;?>"><?php getTitle($item, $that);?></td>
			<?php $title_show = true; endif;?>
			<td><?php echo JText::_('CSALE');?></td>
			<td>
				<?php
					if(!isset($that->book_field->value['sale']['fix'])){
						$price = $that->book_field->getReadyPrice($that->book_field->value['sale']);
					} else {
						$price = @$that->book_field->value['sale']['price'];
					}
					echo $that->book_field->nformat($price);
				?>

			 <?php echo $that->book_field->params->get('params.cur_output')?></td>
			<td>&nbsp;</td>
			<td><?php echo $that->cart['sale'][$item->id]?>"
				<?php
					echo isset($that->book_field->units[$that->book_field->value['sale']['unit']]) ? $that->book_field->units[$that->book_field->value['sale']['unit']] : 'No unit';
				?>
			</td>
			<td>&nbsp;</td>
			<td>
				<?php
					$a = $that->cart['sale'][$item->id] * $price;
					$that->total_cat += $a;
					$that->total += $a;
					echo $that->book_field->nformat($a);
				?>
				<?php echo JText::_($that->book_field->params->get('params.cur_output'));?>
			</td>
		</tr>
		<?php endif;?>

		<?php if(isset($that->cart['order'][$item->id])): ?>
		<tr class="item-block">
			<?php if(!$title_show):?>
			<td rowspan="<?php echo $rowspan;?>"><?php getTitle($item, $that);?></td>
			<?php $title_show = true; endif;?>
			<td><?php echo JText::_('CORDER');?></td>
			<td>
				<?php
					if(!isset($that->book_field->value['order']['fix'])){
						$price = $that->book_field->getReadyPrice($that->book_field->value['order']);
					} else {
						$price = @$that->book_field->value['order']['price'];
					}
					echo $that->book_field->nformat($price);
				?>

			<?php echo $that->book_field->params->get('params.cur_output')?></td>
			<td>час.</td>
			<td><?php echo $that->cart['order'][$item->id]?>

				<?php
					echo isset($that->book_field->units[$that->book_field->value['order']['unit']]) ? $that->book_field->units[$that->book_field->value['order']['unit']] : 'No unit';
				?>
			</td>
			<td><?php
				$time = isset($that->cart['time_order'][$item->id]) ? $that->cart['time_order'][$item->id] : 1;
				echo $time;?> сут.</td>
			<td>
				<?php
					$a = $that->cart['order'][$item->id] * $price * $time;
					$that->total_cat += $a;
					$that->total += $a;
					echo $that->book_field->nformat($a);
				?>
				<?php echo JText::_($that->book_field->params->get('params.cur_output'));?>
			</td>
		</tr>
		<?php endif;?>

<?php
	return ob_get_clean();
}

function getField($field, $that, $position = 1)
{
	if(empty($field->result)) return;
	?>
	<div class="field-block <?php echo $that->params->get('tmpl_params.field_view'.$position, 'clearfix')?>">
		<?php if($field->params->get('core.show_lable') > 1):?>
			<div class="label-pos<?php echo $position; ?> <?php echo $that->params->get('tmpl_params.field_label'.$position, '')?> <?php echo $field->class;?>" >
				<?php echo $field->label; ?>
				<?php if($field->params->get('core.icon')):?>
					<img alt="<?php echo strip_tags($field->label)?>" src="<?php echo JURI::root(TRUE)?>/media/mint/icons/16/<?php echo $field->params->get('core.icon');?>" align="absmiddle">
				<?php endif;?>
			</div>
		<?php endif;?>
		<div class="field-pos<?php echo $position; ?> <?php echo $that->params->get('tmpl_params.field_label'.$position, '')?> input-field<?php echo ($field->params->get('core.label_break') > 1 ? '-full' : NULL)?>">
			<?php echo $field->result; ?>
		</div>
	</div>
<?php
}

function getTitle($item, $that){
	$uri = JFactory::getURI();
	$u = $uri->getScheme().'://'.$uri->getHost().'/';
?>
	<a href="<?php echo $u.JRoute::_($item->url);?>">
		<?php echo $item->title?>
	</a>

<?php
}
?>
