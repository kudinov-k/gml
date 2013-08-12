<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

class CustomTemplateHelper
{

	public static function getItemBlock($item, $that)
	{
		$class = '';
		$prefix = $that->params->get('tmpl_params.prefix', '');
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
		<div class="<?php echo $prefix;?>item-block row-fluid<?php echo $class;?> relative_ctrls has-context">
			<!-- Title position 1-->
			<?php if($that->params->get('tmpl_params.title_position', 1) == 1):?>
				<div class="<?php echo $prefix;?>title-position1">
					<?php self::getTitle($item, $that);?>
				</div>
			<?php endif;?>
			<!-- End Title position 1-->

			<div class="pull-left <?php echo $prefix;?>position1">
				<?php if(count($that->pos1)): ?>
					<div class="text-overflow <?php echo $prefix;?>fields-pos1">
						<?php foreach ($that->pos1 AS $id): $id = $that->fields_keys_by_id[$id];?>
							<?php if(!isset($item->fields_by_key[$id])) continue;?>
							<?php $field = $item->fields_by_key[$id];?>
							<?php self::getField($field, $that, 1)?>
						<?php endforeach;?>
					</div>
				<?php endif;?>
			</div>
			<div class="pull-left <?php echo $prefix;?>position2">

				<!-- Title position 3-->
				<?php if($that->params->get('tmpl_params.title_position', 1) == 3):?>
					<div class="<?php echo $prefix;?>title-position3">
					<?php self::getTitle($item, $that);?>
				</div>
				<?php endif;?>
				<!-- End Title position 3-->

				<?php if(count($that->pos2)): ?>
					<div class="text-overflow <?php echo $prefix;?>fields-pos2">
						<?php foreach ($that->pos2 AS $id): $id = $that->fields_keys_by_id[$id];?>
							<?php if(!isset($item->fields_by_key[$id])) continue;?>
							<?php $field = $item->fields_by_key[$id];?>

							<?php self::getField($field, $that, 2)?>

						<?php endforeach;?>
					</div>
				<?php endif;?>
			</div>
			<div class="pull-left <?php echo $prefix;?>position3">
				<?php if(count($that->pos3)): ?>
					<div class="text-overflow <?php echo $prefix;?>fields-pos3">
						<?php foreach ($that->pos3 AS $id): $id = $that->fields_keys_by_id[$id];?>
							<?php if(!isset($item->fields_by_key[$id])) continue;?>
							<?php $field = $item->fields_by_key[$id];?>
							<?php self::getField($field, $that, 3)?>
						<?php endforeach;?>
					</div>
				<?php endif;?>
			</div>
			<div class="clearfix"></div>
			<div class="<?php echo $prefix;?>position4">
				<?php if(count($that->pos4)): ?>
					<div class="text-overflow <?php echo $prefix;?>fields-pos4">
						<?php foreach ($that->pos4 AS $id): $id = $that->fields_keys_by_id[$id];?>
							<?php if(!isset($item->fields_by_key[$id])) continue;?>
							<?php $field = $item->fields_by_key[$id];?>
							<?php self::getField($field, $that, 4)?>
						<?php endforeach;?>
					</div>
				<?php endif;?>

				<?php if($that->params->get('tmpl_params.show_core', 1)):?>
					<?php self::getCoreFields($item, $that);?>
				<?php endif;?>
			</div>

			<!-- Title position 2-->
			<?php if($that->params->get('tmpl_params.title_position', 1) == 2):?>
				<div class="<?php echo $prefix;?>title-position2">
					<?php self::getTitle($item, $that);?>
				</div>
			<?php endif;?>
			<!-- End Title position 2-->

		</div>
	<?php
		return ob_get_clean();
	}

	public static function getField($field, $that, $position = 1)
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

	public static function getTitle($item, $that){
		$prefix = $that->params->get('tmpl_params.prefix', '');
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
				<div class="<?php echo $that->params->get('tmpl_params.title_align', 'pull-left')?>">
					<<?php echo $that->params->get('tmpl_core.title_tag', 'h2');?> class="record-title">
						<?php if( $that->params->get('tmpl_params.title_length', 0) && mb_strlen($item->title) > $that->params->get('tmpl_params.title_length')):?>
							<?php $item->title = mb_substr($item->title, 0, $that->params->get('tmpl_params.title_length')).$that->params->get('tmpl_params.title_end')?>
						<?php endif;?>
						<span class="<?php echo $prefix;?>title-text">
						<?php if(in_array($that->params->get('tmpl_core.item_link'), $that->user->getAuthorisedViewLevels())):?>
							<a <?php echo $item->nofollow ? 'rel="nofollow"' : '';?> href="<?php echo JRoute::_($item->url);?>">
								<?php echo $item->title?>
							</a>
						<?php else :?>
							<?php echo $item->title?>
						<?php endif;?>
						</span>
						<?php echo CEventsHelper::showNum('record', $item->id);?>
					</<?php echo $that->params->get('tmpl_core.title_tag', 'h2');?>>
				</div>
				<div class="clearfix"></div>
			<?php endif;?>
		<?php endif;?>

	<?php
	}

	public static function getCoreFields($item, $that){

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

}

?>