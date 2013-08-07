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
$span = array(1 => 12, 2 => 6, 3 => 4, 4 => 3, 6 => 2);
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
	$query->select('c.*');
	$query->from('#__js_res_categories AS c');
	$query->where('c.id IN (' . implode(',', array_keys($cats)) . ')');
	$query->order('c.lft');
	$db->setQuery($query);
	$sorted_cats = $db->loadColumn();
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

		<?php $k = 0;?>
		<?php foreach ($sorted[$cat_id] AS $item):?>

			<?php if($k % $cols == 0):?>
				<div class="row-fluid">
			<?php endif;?>

			<div class="span<?php echo $span[$cols]?> ">
				<?php echo CustomTemplateHelper::getItemBlock($item, $this); ?>
			</div>

			<?php if($k % $cols == ($cols - 1)):?>
				</div>
			<?php endif; $k++;?>

		<?php endforeach;?>

		<?php if($k % $cols != 0):?>
			</div>
		<?php endif;?>

	<?php endforeach;?>

<?php else:?>

	<?php $k = 0;?>
	<?php foreach ($this->items AS $item):?>

		<?php if($k % $cols == 0):?>
			<div class="row-fluid">
		<?php endif;?>

		<div class="span<?php echo $span[$cols]?> ">
			<?php echo CustomTemplateHelper::getItemBlock($item, $this); ?>
		</div>

		<?php if($k % $cols == ($cols - 1)):?>
			</div>
		<?php endif; $k++;?>

	<?php endforeach;?>

	<?php if($k % $cols != 0):?>
		</div>
	<?php endif;?>

<?php endif;?>
<div class="clearfix"></div>

