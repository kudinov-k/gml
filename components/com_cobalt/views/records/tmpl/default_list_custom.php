<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

require_once (__dir__).'/templateHelper.php';

$this->params = $this->tmpl_params['list'];
JHtml::_('dropdown.init');
$this->pos1 = $this->params->get('tmpl_params.field_id_position_1', array());
$this->pos2 = $this->params->get('tmpl_params.field_id_position_2', array());
$this->pos3 = $this->params->get('tmpl_params.field_id_position_3', array());
$this->pos4 = $this->params->get('tmpl_params.field_id_position_4', array());

$cols = $this->params->get('tmpl_params.columns', 3);
$cat_cols = $this->params->get('tmpl_params.cat_columns', 3);

$prefix = $this->params->get('tmpl_params.prefix', 'default');

$doc = JFactory::getDocument();
$doc->addStyleSheet(JUri::root(true).'/components/com_cobalt/views/records/tmpl/default_list_custom_'.$prefix.'.css');

$db = JFactory::getDbo();
$query = $db->getQuery(true);

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
	$query->select('c.id, c.parent_id');
	$query->from('#__js_res_categories AS c');
	$query->where('c.id IN (' . implode(',', array_keys($cats)) . ')');
	$query->order('c.lft');
	$db->setQuery($query);
	$sorted_cats = $db->loadObjectList('id');
}
?>


<style>
.user-info {
	margin: 0;
}
.avatar {
	text-align: center;
	vertical-align: middle;
}
.table-records tbody tr td {
	vertical-align: <?php echo $this->params->get('tmpl_core.valign') ?>;
}
.record-title {
	margin: 0 0 5px 0;
}
.relative_ctrls {
	position: relative;
}
.user-ctrls {
	position: absolute;
	top:0px;
	right: 0;
}
</style>

<?php if($this->params->get('tmpl_core.show_title_index')):?>
	<h2><?php echo JText::_('CONTHISPAGE')?></h2>
	<ul>
		<?php foreach ($this->items AS $item):?>
			<li><a href="#record<?php echo $item->id?>"><?php echo $item->title?></a></li>
		<?php endforeach;?>
	</ul>
<?php endif;?>

<?php if($this->params->get('tmpl_params.show_cats', 1)):?>
	<?php if($this->params->get('tmpl_params.cat_columns', 1) > 1):?>

		<?php foreach ($sorted_cats AS $cat_id => $cat){
			$sorted_up[$cat->parent_id][] = $cat_id;
		}?>

		<?php
		foreach ($sorted_up AS $parent_id => $cats){

			$parent = $cats_model->getCategoriesById($parent_id);
			?>
			<div class="<?php echo $prefix;?>category"><?php echo $parent[0]->title;?></div>
			<div class="<?php echo $prefix;?>category_descr"><?php echo $parent[0]->description;?></div>
			<div class="clearfix"></div>
			<?php

			$span = array(1 => 12, 2 => 6, 3 => 4, 4 => 3, 6 => 2);?>
			<?php while (count($cats) > 0):?>
				<?php
					$sliced_cats = array_splice($cats, 0, $cat_cols);

					if(empty($sliced_cats)) break;
					$sco = $cats_model->getCategoriesById($sliced_cats);
					$has_descr = false;
					foreach ($sco as $sc){
						$sliced_cats_objects[$sc->id] = $sc;
						if ($sc->description)
						{
							$has_descr = true;
						}
					}
				?>

				<div class="row-fluid">
				<?php foreach ($sliced_cats as $cat_id):?>
					<div class="span<?php echo $span[$cat_cols]?> ">
						<div class="<?php echo $prefix;?>subcategory"><?php echo $sliced_cats_objects[$cat_id]->title;?></div>
					</div>
				<?php endforeach;?>
				</div>

				<?php if ($has_descr):?>
					<div class="row-fluid">
						<?php foreach ($sliced_cats as $cat_id):?>
							<div class="span<?php echo $span[$cat_cols]?> <?php echo $prefix;?>subcategory_descr">
								<?php echo $sliced_cats_objects[$cat_id]->description;?>
							</div>
						<?php endforeach;?>
					</div>
				<?php endif;?>

				<div class="row-fluid">
					<?php foreach ($sliced_cats as $cat_id):?>
						<div class="span<?php echo $span[$cat_cols]?> <?php echo $prefix;?>item-bloks">
							<?php echo CustomTemplateHelper::getItems($this, $sorted[$cat_id], $cols); ?>
						</div>
					<?php endforeach;?>
				</div>

			<?php endwhile;?>

	<?php }?>

	<?php else: ?>
		<?php foreach ($sorted_cats AS $cat_id => $cat):?>

			<?php
			$parents = $cats_model->getParentsObjectsByChild($cat_id);
			$f_cat = 0;
			foreach ($parents as $parent)
			{
				if(in_array($parent->id, $showed_parents)) {$f_cat++; continue;}
				?>
				<div class="<?php echo $prefix;?><?php if(!$f_cat):?>category<?php $f_cat++; else:?>subcategory<?php endif;?>"><?php echo $parent->title;?></div>
				<div class="<?php echo $prefix;?><?php if(!$f_cat):?>category<?php $f_cat++; else:?>subcategory<?php endif;?>_descr"><?php echo $parent->description;?></div>
				<div class="clearfix"></div>
				<?php
				$showed_parents[] = $parent->id;
			}
			?>

			<?php echo CustomTemplateHelper::getItems($this, $sorted[$cat_id], $cols);?>

		<?php endforeach;?>
	<?php endif;?>

<?php else:?>

	<?php echo CustomTemplateHelper::getItems($this, $this->items, $cols);?>

<?php endif;?>
<div class="clearfix"></div>

