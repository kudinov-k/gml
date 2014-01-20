<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die('Restricted access');
$params = $this->tmpl_params['cindex'];
$parent_id = ($params->get('tmpl_params.cat_type', 2) == 1 && $this->category->id) ? $this->category->id : 1;

$cats_model = $this->models['categories'];
$cats_model->section = $this->section;
$cats_model->parent_id = $parent_id;
$cats_model->order = $params->get('tmpl_params.cat_ordering', 'c.lft ASC');
$cats_model->levels = $params->get('tmpl_params.subcat_level');
$cats_model->all = 0;
$cats_model->nums = ($params->get('tmpl_params.cat_nums') || $params->get('tmpl_params.subcat_nums'));
$categories = $cats_model->getItems();

$cats = array();
foreach ($categories as $cat)
{
	if ($params->get('tmpl_params.cat_empty', 1)
		|| ( !$params->get('tmpl_params.cat_empty', 1) && ($cat->num_current || $cat->num_all) ) )
	$cats[] = $cat;
}
if(!count($cats)) return;

$cols = $params->get('tmpl_params.cat_cols', 3);
$rows = count($cats) / $params->get('tmpl_params.cat_cols',  3);
$rows = ceil($rows);
$ind = 0;
$span = array(1=>12,2=>6,3=>4,4=>3,6=>12);
?>

<?php if($this->tmpl_params['cindex']->get('tmpl_core.show_title', 1)):?>
	<h2><?php echo JText::_($this->tmpl_params['cindex']->get('tmpl_core.title_label', 'Category Index'))?></h2>
<?php endif;?>

<?php for($i = 0; $i < $rows; $i ++):?>
	<div class="row-fluid">
		<?php for($c = 0; $c < $cols; $c++):?>
			<div class="span<?php echo $span[$cols]?> category-box">
				<?php if ($ind < count($cats)): ?>
					<?php $category = $cats[$ind]; ?>
						<?php if($params->get('tmpl_params.cat_img', 1) && $category->image):?>
							<div style="text-align: center;">
								<?php $url = CImgHelper::getThumb(JPATH_ROOT.DIRECTORY_SEPARATOR.$category->image, $params->get('tmpl_params.cat_img_width', 200), $params->get('tmpl_params.cat_img_height', 200), 'catindex');?>
								<img class="category_icon" alt="<?php echo $category->title; ?>" src="<?php echo $url;?>">
							</div>
							<br>
						<?php endif;?>

						<<?php echo $params->get('tmpl_params.tag', 'span')?> class="ci-cat-title">
							<a href="<?php echo JRoute::_($category->link)?>">
								<?php if($category->id == JRequest::getInt('cat_id')):?>
									<b><?php echo $category->title; ?></b>
								<?php else:?>
									<?php echo $category->title; ?>
								<?php endif;?>
							</a>
						</<?php echo $params->get('tmpl_params.tag', 'span')?>>

						<br />
						<?php if($params->get('tmpl_params.cat_descr', 0) && $category->description):?>
							<span class="ci-cat-descr">
								<?php echo strip_tags($category->{'descr_'.$params->get('tmpl_params.cat_descr')});?>
							</span>
						<?php endif;?>
						<?php if(count($category->children)):?>
							<div class="subcat" id="subcat<?php echo $category->id;?>">
								<?php getChilds($category, $params);?>
							</div>
						<?php endif;?>
					<?php $ind++?>
				<?php endif;?>
			</div>
		<?php endfor;?>
	</div>
<?php endfor;?>
<br>

<?php function getChilds($category, $params, $class="nav") { ?>
	<?php
	$cats = $category->children;
	$cols = $params->get('tmpl_params.sub_cat_cols', 3);
	$rows = count($cats) / $cols;
	$rows = ceil($rows);
	$ind = 0;
	$span = array(1=>12,2=>6,3=>4,4=>3,6=>12);
	?>

	<?php for($i = 0; $i < $rows; $i ++):?>
	<div class="row-fluid cat-center-align">
		<?php for($c = 0; $c < $cols; $c++):?>
			<div class="span<?php echo $span[$cols]?> sub-category-box">
				<?php if ($ind < count($cats)): ?>
					<?php $category = $cats[$ind]; ?>
						<?php if($params->get('tmpl_params.sub_cat_img', 1) && $category->image):?>
							<div style="text-align: center;">
								<?php $url = CImgHelper::getThumb(JPATH_ROOT.DIRECTORY_SEPARATOR.$category->image, $params->get('tmpl_params.sub_cat_img_width', 200), $params->get('tmpl_params.sub_cat_img_height', 200), 'catindex');?>
								<img class="category_icon" alt="<?php echo $category->title; ?>" src="<?php echo $url;?>">
							</div>
							<br>
						<?php endif;?>

						<<?php echo $params->get('tmpl_params.sub_tag', 'span')?> class="ci-subcat-title">
							<a href="<?php echo JRoute::_($category->link)?>">
								<?php if($category->id == JRequest::getInt('cat_id')):?>
									<b><?php echo $category->title; ?></b>
								<?php else:?>
									<?php echo $category->title; ?>
								<?php endif;?>
							</a>
						</<?php echo $params->get('tmpl_params.sub_tag', 'span')?>>
						<br />

						<?php if($params->get('tmpl_params.sub_cat_descr', 0) && $category->description):?>
							<span class="ci-subcat-descr">
								<?php echo strip_tags($category->{'descr_'.$params->get('tmpl_params.sub_cat_descr')});?>
							</span>
						<?php endif;?>

						<?php if(count($category->children)):?>
							<div class="subcat" id="subcat<?php echo $category->id;?>">
								<?php getChilds($category, $params);?>
							</div>
						<?php endif;?>
					<?php $ind++?>
				<?php endif;?>
			</div>
		<?php endfor;?>
	</div>
	<?php endfor;?>
<?php } ?>