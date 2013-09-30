<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

$this->params = $this->tmpl_params['list'];
JHtml::_('dropdown.init');
$this->pos1 = $this->params->get('tmpl_params.field_id_position_1', array());

$span = array(1 => 12, 2 => 6, 3 => 4, 4 => 3, 6 => 2);
$prefix = $this->params->get('tmpl_params.prefix', '');


$app = JFactory::getApplication();
$this->cart = $app->getUserState('booking_cart', array());

$db = JFactory::getDbo();
$query = $db->getQuery(true);
$this->mod_params = new JRegistry($app->input->getString('mod_params', ''));
$total = 0;

$this->rmodel = JModelLegacy::getInstance('Fields', 'CobaltModel');

if($this->params->get('tmpl_params.show_cats', 1))
{
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
}
?>

<?php //if($this->params->get('tmpl_params.show_cats', 1)):?>
	<form method="post" action="index.php?option=com_cobalt&task=ajax.field_call&field_id=<?php echo $this->mod_params->get('booking_id');?>&func=updateCart&return=<?php echo $app->input->getBase64('return', '');?>">
	<?php foreach ($sorted_cats AS $cat_id):?>

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
				<?php foreach ($sorted[$cat_id] AS $item):?>
					<?php
						$this->book_field = $this->rmodel->getField($this->mod_params->get('booking_id'), $item->type_id, $item->id);
						$this->book_field->units = explode("\n", $this->book_field->params->get('params.unit'));
					?>
					<?php echo getItemBlock($item, $this); ?>

				<?php endforeach;?>
			</tbody>
		</table>
	<?php endforeach;?>
	<button type="submit" class="btn btn-small btn-success"><?php echo JText::_('RECALC');?></button>
	</form>


<?php /*else:?>

	<?php $k = 0;?>
	<?php foreach ($this->items AS $item):?>
		<?php
			$this->book_field = $this->rmodel->getField($this->mod_params->get('booking_id'), $item->type_id, $item->id);
			if(!is_array($this->book_field->value))
			{
				settype($this->book_field->value, 'array');
				$this->book_field->value['rent'] = isset($this->book_field->value[0]) ? $this->book_field->value[0] : 1 ;
				$this->book_field->value['sale'] = 0;
			}
		?>
		<?php $total += @$this->cart['rent'][$item->id] * floatval(str_replace(',', '', $this->book_field->value['rent']));?>
		<?php $total += @$this->cart['sale'][$item->id] * floatval(str_replace(',', '', $this->book_field->value['sale']));?>
		<?php if($k % $cols == 0):?>
			<div class="row-fluid">
		<?php endif;?>

		<div class="span<?php echo $span[$cols]?> ">
			<?php echo getItemBlock($item, $this); ?>
		</div>

		<?php if($k % $cols == ($cols - 1)):?>
			</div>
		<?php endif; $k++;?>

	<?php endforeach;?>

	<?php if($k % $cols != 0):?>
		</div>
	<?php endif;*/?>

<?php //endif;?>

<div class="clearfix"></div>


<?php
function getItemBlock($item, $that, $core_fields = '')
{
	$prefix = $that->params->get('tmpl_params.prefix', '');

	ob_start();
	$rowspan = 0;
	if(isset($that->cart['rent'][$item->id]))
		$rowspan += 1;
	if(isset($that->cart['sale'][$item->id]))
		$rowspan += 1;
	if(isset($that->cart['order'][$item->id]))
		$rowspan += 1;

?>

		<?php if(isset($that->cart['rent'][$item->id])): ?>
		<tr class="<?php echo $prefix;?>item-block">
			<td rowspan="<?php echo $rowspan;?>"><?php getTitle($item, $that);?></td>
			<td><?php echo JText::_('CRENT');?></td>
			<td><?php echo $that->book_field->value['rent']['price']?> <?php echo $that->book_field->params->get('params.cur_output')?></td>
			<td>сут.</td>
			<td><input type="text" class="input-mini" name="amount[rent][<?php echo $item->id ?>]" value="<?php echo $that->cart['rent'][$item->id]?>"/>

				<?php
					echo isset($that->book_field->units[$that->book_field->value['rent']['unit']]) ? $that->book_field->units[$that->book_field->value['rent']['unit']] : 'No unit';
				?>
			</td>
			<td>
				<input type="text" class="input-mini" name="days[rent][<?php echo $item->id ?>]"
					value="<?php echo isset($that->cart['time_rent'][$item->id]) ? $that->cart['time_rent'][$item->id] : 1;?>"/>
			сут.</td>
			<td>
				<?php
					echo $that->cart['rent'][$item->id] * $that->book_field->value['rent']['price'] * (isset($that->cart['time_rent'][$item->id]) ? $that->cart['time_rent'][$item->id] : 1);
				?>
				<?php echo $that->book_field->params->get('params.cur_output')?>
			</td>
		</tr>
		<?php endif;?>


		<?php if(isset($that->cart['sale'][$item->id])): ?>
		<tr class="<?php echo $prefix;?>item-block">
			<td><?php echo JText::_('CSALE');?></td>
			<td><?php echo $that->book_field->value['sale']['price']?> <?php echo $that->book_field->params->get('params.cur_output')?></td>
			<td>&nbsp;</td>
			<td><input type="text" class="input-mini" name="amount[sale][<?php echo $item->id ?>]" value="<?php echo $that->cart['sale'][$item->id]?>"/>

				<?php
					echo isset($that->book_field->units[$that->book_field->value['sale']['unit']]) ? $that->book_field->units[$that->book_field->value['sale']['unit']] : 'No unit';
				?>
			</td>
			<td>&nbsp;</td>
			<td>
				<?php
					echo $that->cart['sale'][$item->id] * $that->book_field->value['sale']['price'] * 1;
				?>
				<?php echo $that->book_field->params->get('params.cur_output')?>
			</td>
		</tr>
		<?php endif;?>

		<?php if(isset($that->cart['order'][$item->id])): ?>
		<tr class="<?php echo $prefix;?>item-block">
			<td><?php echo JText::_('CORDER');?></td>
			<td><?php echo $that->book_field->value['order']['price']?> <?php echo $that->book_field->params->get('params.cur_output')?></td>
			<td>час.</td>
			<td><input type="text" class="input-mini" name="amount[order][<?php echo $item->id ?>]" value="<?php echo $that->cart['order'][$item->id]?>"/>

				<?php
					echo isset($that->book_field->units[$that->book_field->value['order']['unit']]) ? $that->book_field->units[$that->book_field->value['order']['unit']] : 'No unit';
				?>
			</td>
			<td>
				<input type="text" class="input-mini" name="days[order][<?php echo $item->id ?>]"
					value="<?php echo isset($that->cart['time_order'][$item->id]) ? $that->cart['time_order'][$item->id] : 1;?>"/>
			сут.</td>
			<td>
				<?php
					echo $that->cart['order'][$item->id] * $that->book_field->value['order']['price'] * (isset($that->cart['time_order'][$item->id]) ? $that->cart['time_order'][$item->id] : 1);
				?>
				<?php echo $that->book_field->params->get('params.cur_output')?>
			</td>
		</tr>
		<?php endif;?>

<?php
	return ob_get_clean();
}

function getField($field, $that, $position = 1)
{
	if(empty($field->result)) return;
	$prefix = $that->params->get('tmpl_params.prefix', '');
?>
<div class="<?php echo $prefix;?>field-block <?php echo $that->params->get('tmpl_params.field_view'.$position, 'clearfix')?>">
	<?php if($field->params->get('core.show_lable') > 1):?>
		<div class="<?php echo $prefix;?>label-pos<?php echo $position; ?> <?php echo $that->params->get('tmpl_params.field_label'.$position, '')?> <?php echo $field->class;?>" >
			<?php echo $field->label; ?>
			<?php if($field->params->get('core.icon')):?>
				<img alt="<?php echo strip_tags($field->label)?>" src="<?php echo JURI::root(TRUE)?>/media/mint/icons/16/<?php echo $field->params->get('core.icon');?>" align="absmiddle">
			<?php endif;?>
		</div>
	<?php endif;?>
	<div class="<?php echo $prefix;?>field-pos<?php echo $position; ?> <?php echo $that->params->get('tmpl_params.field_label'.$position, '')?> input-field<?php echo ($field->params->get('core.label_break') > 1 ? '-full' : NULL)?>">
		<?php echo $field->result; ?>
	</div>
</div>
<?php
}

function getTitle($item, $that){
	$prefix = $that->params->get('tmpl_params.prefix', '');
?>
	<?php /*if($that->user->get('id')):?>
		<div class="user-ctrls">
			<div class="btn-group" style="display: none;">
				<?php echo HTMLFormatHelper::bookmark($item, $that->submission_types[$item->type_id], $that->params);?>
				<?php echo HTMLFormatHelper::follow($item, $that->section);?>
				<?php echo HTMLFormatHelper::repost($item, $that->section);?>
				<?php echo HTMLFormatHelper::compare($item, $that->submission_types[$item->type_id], $that->section);?>
				<?php if($item->controls):?>
					<a href="#" data-toggle="dropdown" class="dropdown-toggle btn btn-mini">
						<img width="16" height="16" alt="<?php echo JText::_('COPTIONS')?>" src="<?php echo JURI::root(TRUE)?>/media/mint/icons/16/gear.png">
					</a>
					<ul class="dropdown-menu">
						<?php echo list_controls($item->controls);?>
					</ul>
				<?php endif;?>
			</div>
		</div>
	<?php endif;*/?>

	<?php /*if($that->params->get('tmpl_core.item_title')):?>
		<?php if($that->submission_types[$item->type_id]->params->get('properties.item_title')):?>
			<div class="<?php echo $that->params->get('tmpl_params.title_align', 'pull-left')?>">
				<<?php echo $that->params->get('tmpl_core.title_tag', 'h2');?> class="record-title">
					<?php if( $that->params->get('tmpl_params.title_length', 0) && mb_strlen($item->title) > $that->params->get('tmpl_params.title_length')):?>
						<?php $item->title = mb_substr($item->title, 0, $that->params->get('tmpl_params.title_length')).$that->params->get('tmpl_params.title_end')?>
					<?php endif;*/?>
					<span class="<?php echo $prefix;?>title-text">
					<?php if(in_array($that->params->get('tmpl_core.item_link'), $that->user->getAuthorisedViewLevels())):?>
						<a <?php echo $item->nofollow ? 'rel="nofollow"' : '';?> href="<?php echo JRoute::_($item->url);?>">
							<?php echo $item->title?>
						</a>
					<?php else :?>
						<?php echo $item->title?>
					<?php endif;?>
					</span>
					<?php /*echo CEventsHelper::showNum('record', $item->id);?>
				</<?php echo $that->params->get('tmpl_core.title_tag', 'h2');?>>
			</div>
			<div class="clearfix"></div>
		<?php endif;?>
	<?php endif;*/?>

<?php
}
?>
