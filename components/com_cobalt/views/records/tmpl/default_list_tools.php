<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

$k = $p1 = 0;
$this->params = $this->tmpl_params['list'];
$total_fields_keys = $this->total_fields_keys;
$fh = new FieldHelper($this->fields_keys_by_id, $this->total_fields_keys);
JHtml::_('dropdown.init');
$this->pos1 = $this->params->get('tmpl_params.field_id_position_1', array());
$this->pos2 = $this->params->get('tmpl_params.field_id_position_2', array());
$this->pos3 = $this->params->get('tmpl_params.field_id_position_3', array());
$this->pos4 = $this->params->get('tmpl_params.field_id_position_4', array());


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
	top:-24px;
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

<?php foreach ($this->items AS $item):?>
	<?php echo getItemBlock($item, $this); ?>
<?php endforeach;?>

<?php
function getItemBlock($item, $that, $core_fields = '')
{
	$class = '';
	if($item->featured)
	{
		$class = ' success';
	}
	elseif($item->expired)
	{
		$class = ' error';
	}
	elseif($item->future)
	{
		$class = ' warning';
	}

	ob_start();
?>
	<div class="item-block row-fluid<?php echo $class;?>">
		<!-- Title position 1-->
		<?php if($that->params->get('tmpl_params.title_position', 1) == 1):?>
			<div class="title-position1 relative_ctrls has-context">
				<?php getTitle($item, $that);?>
			</div>
		<?php endif;?>
		<!-- End Title position 1-->

		<div class="pull-left position1">
			<?php if(count($that->pos1)): ?>
				<dl class="text-overflow">
					<?php foreach ($that->pos1 AS $id): $id = $that->fields_keys_by_id[$id];?>
						<?php if(empty($item->fields_by_key[$id]->result)) continue;?>
						<?php $field = $item->fields_by_key[$id];?>
						<?php if($field->params->get('core.show_lable') > 1 && $that->params->get('tmpl_params.field_labels') == 1):?>
							<dt id="<?php echo $field->id;?>-lbl" for="field_<?php echo $field->id;?>" class="<?php echo $field->class;?>" >
								<?php echo $field->label; ?>
								<?php if($field->params->get('core.icon')):?>
									<img alt="<?php strip_tags($field->label)?>" src="<?php echo JURI::root(TRUE)?>/media/mint/icons/16/<?php echo $field->params->get('core.icon');?>" align="absmiddle">
								<?php endif;?>
							</dt>
						<?php endif;?>
						<dd class="input-field<?php echo ($field->params->get('core.label_break') > 1 ? '-full' : NULL)?>">
							<?php echo $field->result; ?>
						</dd>
						<?php if(isset($that->total_fields_keys[$id])) unset($that->total_fields_keys[$id]);?>
					<?php endforeach;?>
				</dl>
			<?php endif;?>
		</div>
		<div class="pull-left position2">

			<!-- Title position 3-->
			<?php if($that->params->get('tmpl_params.title_position', 1) == 3):?>
				<div class="title-position3 relative_ctrls has-context">
				<?php getTitle($item, $that);?>
			</div>
			<?php endif;?>
			<!-- End Title position 3-->

			<?php if(count($that->pos2)): ?>
				<dl class="text-overflow">
					<?php foreach ($that->pos2 AS $id): $id = $that->fields_keys_by_id[$id];?>
						<?php if(empty($item->fields_by_key[$id]->result)) continue;?>
						<?php $field = $item->fields_by_key[$id];?>
						<?php if($field->params->get('core.show_lable') > 1 && $that->params->get('tmpl_params.field_labels') == 1):?>
							<dt id="<?php echo $field->id;?>-lbl" for="field_<?php echo $field->id;?>" class="<?php echo $field->class;?>" >
								<?php echo $field->label; ?>
								<?php if($field->params->get('core.icon')):?>
									<img alt="<?php strip_tags($field->label)?>" src="<?php echo JURI::root(TRUE)?>/media/mint/icons/16/<?php echo $field->params->get('core.icon');?>" align="absmiddle">
								<?php endif;?>
							</dt>
						<?php endif;?>
						<dd class="input-field<?php echo ($field->params->get('core.label_break') > 1 ? '-full' : NULL)?>">
							<?php echo $field->result; ?>
						</dd>
						<?php if(isset($that->total_fields_keys[$id])) unset($that->total_fields_keys[$id]);?>
					<?php endforeach;?>
				</dl>
			<?php endif;?>
		</div>
		<div class="pull-left position3">
			<?php if(count($that->pos3)): ?>
				<dl class="text-overflow">
					<?php foreach ($that->pos3 AS $id): $id = $that->fields_keys_by_id[$id];?>
						<?php if(empty($item->fields_by_key[$id]->result)) continue;?>
						<?php $field = $item->fields_by_key[$id];?>
						<?php if($field->params->get('core.show_lable') > 1 && $that->params->get('tmpl_params.field_labels') == 1):?>
							<dt id="<?php echo $field->id;?>-lbl" for="field_<?php echo $field->id;?>" class="<?php echo $field->class;?>" >
								<?php echo $field->label; ?>
								<?php if($field->params->get('core.icon')):?>
									<img alt="<?php strip_tags($field->label)?>" src="<?php echo JURI::root(TRUE)?>/media/mint/icons/16/<?php echo $field->params->get('core.icon');?>" align="absmiddle">
								<?php endif;?>
							</dt>
						<?php endif;?>
						<dd class="input-field<?php echo ($field->params->get('core.label_break') > 1 ? '-full' : NULL)?>">
							<?php echo $field->result; ?>
						</dd>
						<?php if(isset($that->total_fields_keys[$id])) unset($that->total_fields_keys[$id]);?>
					<?php endforeach;?>
				</dl>
			<?php endif;?>
		</div>
		<div class="clearfix"></div>
		<div class="position4">
			<?php if(count($that->pos4)): ?>
				<dl class="text-overflow">
					<?php foreach ($that->pos4 AS $id): $id = $that->fields_keys_by_id[$id];?>
						<?php if(empty($item->fields_by_key[$id]->result)) continue;?>
						<?php $field = $item->fields_by_key[$id];?>
						<?php if($field->params->get('core.show_lable') > 1 && $that->params->get('tmpl_params.field_labels') == 1):?>
							<dt id="<?php echo $field->id;?>-lbl" for="field_<?php echo $field->id;?>" class="<?php echo $field->class;?>" >
								<?php echo JText::_($field->label); ?>
								<?php if($field->params->get('core.icon')):?>
									<img alt="<?php strip_tags($field->label)?>" src="<?php echo JURI::root(TRUE)?>/media/mint/icons/16/<?php echo $field->params->get('core.icon');?>" align="absmiddle">
								<?php endif;?>
							</dt>
						<?php endif;?>
						<dd class="input-field<?php echo ($field->params->get('core.label_break') > 1 ? '-full' : NULL)?>">
							<?php echo $field->result; ?>
						</dd>
						<?php if(isset($that->total_fields_keys[$id])) unset($that->total_fields_keys[$id]);?>
					<?php endforeach;?>
				</dl>
			<?php endif;?>

			<?php foreach ($that->total_fields_keys AS $field):?>
				<dl class="text-overflow">
					<dt id="<?php echo $field->id;?>-lbl" for="field_<?php echo $field->id;?>" class="<?php echo $field->class;?>" >
						<?php if($field->params->get('core.icon') && $that->params->get('tmpl_params.item_icon_fields')):?>
							<img src="<?php echo JURI::root(TRUE);?>/media/mint/icons/16/<?php echo $field->params->get('core.icon');?>" align="absmiddle" />
						<?php endif;?>
						<?php echo JText::_($field->label);?>
					</dt>
				</dl>
			<?php endforeach;?>
			<?php if($that->params->get('tmpl_params.show_core', 1)):?>
				<?php getCoreFields($item, $that);?>
			<?php endif;?>
		</div>

		<!-- Title position 2-->
		<?php if($that->params->get('tmpl_params.title_position', 1) == 2):?>
			<div class="title-position2 relative_ctrls has-context">
				<?php getTitle($item, $that);?>
			</div>
		<?php endif;?>
		<!-- End Title position 2-->

	</div>
<?php
	return ob_get_clean();
}

function getTitle($item, $that){
?>
	<?php if($that->user->get('id')):?>
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
	<?php endif;?>

	<?php if($that->params->get('tmpl_core.item_title')):?>
		<?php if($that->submission_types[$item->type_id]->params->get('properties.item_title')):?>
			<div class="pull-left">
				<<?php echo $that->params->get('tmpl_core.title_tag', 'h2');?> class="record-title">
					<?php if(in_array($that->params->get('tmpl_core.item_link'), $that->user->getAuthorisedViewLevels())):?>
						<a <?php echo $item->nofollow ? 'rel="nofollow"' : '';?> href="<?php echo JRoute::_($item->url);?>">
							<?php echo $item->title?>
						</a>
					<?php else :?>
						<?php echo $item->title?>
					<?php endif;?>

					<?php echo CEventsHelper::showNum('record', $item->id);?>
				</<?php echo $that->params->get('tmpl_core.title_tag', 'h2');?>>
			</div>
			<div class="clearfix"></div>
		<?php endif;?>
	<?php endif;?>

<?php
}

function getCoreFields($item, $that){

?>
	<table class="table-records <?php echo implode(' ', (array)$that->params->get('tmpl_core.table_style')); ?>">
	<?php if($that->params->get('tmpl_params.table_header', 1)):?>
		<thead>
			<tr>
				<?php if($that->params->get('tmpl_core.item_rating') == 1):?>
					<th>
						<?php if($that->params->get('tmpl_params.item_icon_rating')):?>
							<img src="<?php echo JURI::root(TRUE);?>/media/mint/icons/16/tick.png" align="absmiddle" />
						<?php endif;?>
						<?php echo JText::_('CRATING');?>
					</th>
				<?php endif;?>

				<?php if($that->params->get('tmpl_core.item_author_avatar') == 1):?>
					<th>
						<?php echo JText::_('CAVATAR');?>
					</th>
				<?php endif;?>

				<?php if($that->params->get('tmpl_core.item_author') == 1):?>
					<th nowrap="nowrap">
						<?php if($that->params->get('tmpl_params.item_icon_author')):?>
							<img src="<?php echo JURI::root(TRUE);?>/media/mint/icons/16/user.png" align="absmiddle" />
						<?php endif;?>
						<?php echo JText::_('CAUTHOR');?>
					</th>
				<?php endif;?>

				<?php if($that->params->get('tmpl_core.item_type') == 1):?>
					<th>
						<?php if($that->params->get('tmpl_params.item_icon_type')):?>
							<img src="<?php echo JURI::root(TRUE);?>/media/mint/icons/16/block.png" align="absmiddle" />
						<?php endif;?>
						<?php echo JText::_('CTYPE')?>
					</th>
				<?php endif;?>

				<?php if($that->params->get('tmpl_core.item_user_categories') == 1 && $that->section->params->get('personalize.pcat_submit')):?>
					<th nowrap="nowrap">
						<?php if($that->params->get('tmpl_params.item_icon_user_categories')):?>
							<img src="<?php echo JURI::root(TRUE);?>/media/mint/icons/16/category.png" align="absmiddle" />
						<?php endif;?>
						<?php echo JText::_('CCATEGORY');?>
					</th>
				<?php endif;?>

				<?php if($that->params->get('tmpl_core.item_categories') == 1 && $that->section->categories):?>
					<th nowrap="nowrap">
						<?php if($that->params->get('tmpl_params.item_icon_categories')):?>
							<img src="<?php echo JURI::root(TRUE);?>/media/mint/icons/16/category.png" align="absmiddle" />
						<?php endif;?>
						<?php echo JText::_($that->params->get('tmpl_core.lbl_category', 'CCATEGORY'));?>
					</th>
				<?php endif;?>

				<?php if($that->params->get('tmpl_core.item_ctime') == 1):?>
					<th nowrap="nowrap">
						<?php if($that->params->get('tmpl_params.item_icon_ctime')):?>
							<img src="<?php echo JURI::root(TRUE);?>/media/mint/icons/16/calendar-day.png" align="absmiddle" />
						<?php endif;?>
						<?php echo JText::_('CCREATED');?>
					</th>
				<?php endif;?>

				<?php if($that->params->get('tmpl_core.item_mtime') == 1):?>
					<th nowrap="nowrap">
						<?php if($that->params->get('tmpl_params.item_icon_mtime')):?>
							<img src="<?php echo JURI::root(TRUE);?>/media/mint/icons/16/calendar-day.png" align="absmiddle" />
						<?php endif;?>
						<?php echo JText::_('CCHANGED');?>
					</th>
				<?php endif;?>

				<?php if($that->params->get('tmpl_core.item_extime') == 1):?>
					<th nowrap="nowrap">
						<?php if($that->params->get('tmpl_params.item_icon_extime')):?>
							<img src="<?php echo JURI::root(TRUE);?>/media/mint/icons/16/calendar-day.png" align="absmiddle" />
						<?php endif;?>
						<?php echo JText::_('CEXPIRE');?>
					</th>
				<?php endif;?>

				<?php if($that->params->get('tmpl_core.item_comments_num') == 1):?>
					<th nowrap="nowrap">
						<span rel="tooltip" data-original-title="<?php echo JText::_('CCOMMENTS');?>">
							<?php if($that->params->get('tmpl_params.item_icon_comments_num')):?>
								<img src="<?php echo JURI::root(TRUE);?>/media/mint/icons/16/balloon-left.png" align="absmiddle" />
							<?php else:?>
								<?php echo JString::substr(JText::_('CCOMMENTS'), 0, 1)?>
							<?php endif;?>
						</span>
					</th>
				<?php endif;?>

				<?php if($that->params->get('tmpl_core.item_vote_num') == 1):?>
					<th nowrap="nowrap">
						<span rel="tooltip" data-original-title="<?php echo JText::_('CVOTES');?>">
							<?php if($that->params->get('tmpl_params.item_icon_vote_num')):?>
								<img src="<?php echo JURI::root(TRUE);?>/media/mint/icons/16/star.png" align="absmiddle" />
							<?php else:?>
								<?php echo JString::substr(JText::_('CVOTES'), 0, 1)?>
							<?php endif;?>
						</span>
					</th>
				<?php endif;?>

				<?php if($that->params->get('tmpl_core.item_favorite_num') == 1):?>
					<th nowrap="nowrap">
						<span rel="tooltip" data-original-title="<?php echo JText::_('CFAVORITE');?>">
							<?php if($that->params->get('tmpl_params.item_icon_favorite_num')):?>
								<img src="<?php echo JURI::root(TRUE);?>/media/mint/icons/16/bookmark.png" align="absmiddle" />
							<?php else:?>
								<?php echo JString::substr(JText::_('CFAVORITE'), 0, 1)?>
							<?php endif;?>
						</span>
					</th>
				<?php endif;?>

				<?php if($that->params->get('tmpl_core.item_follow_num') == 1):?>
					<th nowrap="nowrap">
						<span rel="tooltip" data-original-title="<?php echo JText::_('CFOLLOWERS');?>">
							<?php if($that->params->get('tmpl_params.item_icon_follow_num')):?>
								<img src="<?php echo JURI::root(TRUE);?>/media/mint/icons/16/follow1.png" align="absmiddle" />
							<?php else:?>
								<?php echo JString::substr(JText::_('CFOLLOWERS'), 0, 1)?>
							<?php endif;?>
						</span>
					</th>
				<?php endif;?>

				<?php if($that->params->get('tmpl_core.item_hits') == 1):?>
					<th nowrap="nowrap" width="1%">
						<span rel="tooltip" data-original-title="<?php echo JText::_('CHITS');?>">
							<?php if($that->params->get('tmpl_params.item_icon_hits')):?>
								<img src="<?php echo JURI::root(TRUE);?>/media/mint/icons/16/hand-point-090.png" align="absmiddle" />
							<?php else:?>
								<?php echo JString::substr(JText::_('CHITS'), 0, 1)?>
							<?php endif;?>
						</span>
					</th>
				<?php endif;?>
			</tr>
		</thead>
	<?php endif;?>
		<tbody>
			<tr>
				<?php if($that->params->get('tmpl_core.item_rating') == 1):?>
					<td nowrap="nowrap"><?php echo $item->rating;?></td>
				<?php endif;?>

				<?php if($that->params->get('tmpl_core.item_author_avatar') == 1):?>
					<td>
						<img src="<?php echo CCommunityHelper::getAvatar($item->user_id, $that->params->get('tmpl_core.item_author_avatar_width', 40), $that->params->get('tmpl_core.item_author_avatar_height', 40));?>" />
					</td>
				<?php endif;?>

				<?php if($that->params->get('tmpl_core.item_author') == 1):?>
					<td nowrap="nowrap"><?php echo CCommunityHelper::getName($item->user_id, $that->section);?>
					<?php if($that->params->get('tmpl_core.item_author_filter') /* && $item->user_id */):?>
						<?php echo FilterHelper::filterButton('filter_user', $item->user_id, NULL, JText::sprintf('CSHOWALLUSERREC', CCommunityHelper::getName($item->user_id, $that->section, array('nohtml' => 1))), $that->section);?>
					<?php endif;?>
					</td>
				<?php endif;?>

				<?php if($that->params->get('tmpl_core.item_type') == 1):?>
					<td nowrap="nowrap"><?php echo $item->type_name;?>
					<?php if($that->params->get('tmpl_core.item_type_filter')):?>
						<?php echo FilterHelper::filterButton('filter_type', $item->type_id, NULL, JText::sprintf('CSHOWALLTYPEREC', $item->type_name), $that->section);?>
					<?php endif;?>
					</td>
				<?php endif;?>

				<?php if($that->params->get('tmpl_core.item_user_categories') == 1 && $that->section->params->get('personalize.pcat_submit')):?>
					<td><?php echo $item->ucatname;?></td>
				<?php endif;?>

				<?php if($that->params->get('tmpl_core.item_categories') == 1 && $that->section->categories):?>
					<td><?php echo implode(', ', $item->categories_links);?></td>
				<?php endif;?>

				<?php if($that->params->get('tmpl_core.item_ctime') == 1):?>
					<td><?php echo JHtml::_('date', $item->created, $that->params->get('tmpl_core.item_time_format'));?></td>
				<?php endif;?>

				<?php if($that->params->get('tmpl_core.item_mtime') == 1):?>
					<td><?php echo JHtml::_('date', $item->modify, $that->params->get('tmpl_core.item_time_format'));?></td>
				<?php endif;?>

				<?php if($that->params->get('tmpl_core.item_extime') == 1):?>
					<td><?php echo ( $item->expire ? JHtml::_('date', $item->expire, $that->params->get('tmpl_core.item_time_format')) : JText::_('CNEVER'));?></td>
				<?php endif;?>

				<?php if($that->params->get('tmpl_core.item_comments_num') == 1):?>
					<td><?php echo CommentHelper::numComments($that->submission_types[$item->type_id], $item);?></td>
				<?php endif;?>

				<?php if($that->params->get('tmpl_core.item_vote_num') == 1):?>
					<td><?php echo $item->votes;?></td>
				<?php endif;?>


				<?php if($that->params->get('tmpl_core.item_favorite_num') == 1):?>
					<td><?php echo $item->favorite_num;?></td>
				<?php endif;?>

				<?php if($that->params->get('tmpl_core.item_follow_num') == 1):?>
					<td><?php echo $item->subscriptions_num;?></td>
				<?php endif;?>

				<?php if($that->params->get('tmpl_core.item_hits') == 1):?>
					<td><?php echo $item->hits;?></td>
				<?php endif;?>
			</tr>
		</tbody>
	</table>
<?php

}
?>
