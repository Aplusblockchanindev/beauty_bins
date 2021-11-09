<?php
/**
 * @package    inGallery
 * @subpackage com_ingallery
 * @license  http://www.gnu.org/licenseses/gpl-3.0.html GNU/GPL
 *
 * Created by Oleg Micriucov for Joomla! 3.x
 * https://allforjoomla.com
 *
 */
defined('_JEXEC') or die(':)');

JHtml::_('jquery.framework');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$app       = JFactory::getApplication();
$user      = JFactory::getUser();
$userId    = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$columns = 8;

$doc = JFactory::getDocument();
$doc->addScriptDeclaration('
    (function($){
        $(document).ready(function(){
            $("input.ingallery-shortcode").on("click",function(){ $(this).select(); });
        });
    })(jQuery);
');

?>

<form action="<?php echo JRoute::_('index.php?option=com_ingallery&view=galleries'); ?>" method="post" name="adminForm" id="adminForm">
<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
        <?php
		echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
		?>
		<?php if (empty($this->items)) : ?>
			<div class="alert alert-no-items">
				<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
			</div>
		<?php else : ?>
			<table class="table table-striped" id="articleList">
				<thead>
					<tr>
                        <th width="1%" class="center">
							<?php echo JHtml::_('grid.checkall'); ?>
						</th>
						<th width="1%" class="nowrap center hidden-phone">
							<?php echo JHtml::_('searchtools.sort', '', 'a.id', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
						</th>
						<th>
							<?php echo JHtml::_('searchtools.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
						</th>
                        <th>
							<?php echo JText::_('COM_INGALLERY_SHORTCODE'); ?>
						</th>
						<th width="10%" class="nowrap hidden-phone">
							<?php echo JHtml::_('searchtools.sort',  'JGLOBAL_FIELD_CREATED_BY_LABEL', 'a.created_by_name', $listDirn, $listOrder); ?>
						</th>
						<th width="10%" class="nowrap hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'JGLOBAL_FIELD_CREATED_LABEL', 'a.created_at', $listDirn, $listOrder); ?>
						</th>
                        <th width="10%" class="nowrap hidden-phone">
							<?php echo JHtml::_('searchtools.sort',  'JGLOBAL_FIELD_MODIFIED_BY_LABEL', 'a.updated_by_name', $listDirn, $listOrder); ?>
						</th>
						<th width="10%" class="nowrap hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'JGLOBAL_FIELD_MODIFIED_LABEL', 'a.updated_at', $listDirn, $listOrder); ?>
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="<?php echo $columns; ?>">
						</td>
					</tr>
				</tfoot>
				<tbody>
				<?php foreach ($this->items as $i => $item) :
					$canCreate  = $user->authorise('core.create',     'com_ingallery');
					$canEdit    = $user->authorise('core.edit',       'com_ingallery');
					$canCheckin = $user->authorise('core.manage',     'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
					$canEditOwn = $user->authorise('core.edit.own',   'com_ingallery') && $item->created_by == $userId;
					?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="center">
							<?php echo JHtml::_('grid.id', $i, $item->id); ?>
						</td>
                        <td class="hidden-phone">
							<?php echo (int) $item->id; ?>
						</td>
						<td class="has-context">
							<div class="break-word">
								<?php if ($item->checked_out) : ?>
									<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'galleries.', $canCheckin); ?>
								<?php endif; ?>
								<?php if ($canEdit || $canEditOwn) : ?>
									<a class="hasTooltip" href="<?php echo JRoute::_('index.php?option=com_ingallery&task=gallery.edit&id=' . $item->id); ?>" title="<?php echo JText::_('JACTION_EDIT'); ?>">
										<?php echo $this->escape($item->title); ?></a>
								<?php else : ?>
									<span title="<?php echo JText::sprintf('JFIELD_ALIAS_LABEL', $this->escape($item->id)); ?>"><?php echo $this->escape($item->title); ?></span>
								<?php endif; ?>
							</div>
						</td>
                        <td class="nowrap"><input type="text" class="form-control ingallery-shortcode" value="[ingallery id=&quot;<?php echo (int)$item->id; ?>&quot;]" /></td>
						<td class="small hidden-phone">
                            <a class="hasTooltip" href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . (int) $item->created_by); ?>" title="<?php echo JText::_('JAUTHOR'); ?>">
                            <?php echo $this->escape($item->created_by_name); ?></a>
						</td>
						<td class="nowrap small hidden-phone">
							<?php echo JHtml::_('date', $item->created_at, JText::_('COM_INGALLERY_DATE_FORMAT')); ?>
						</td>
                        <td class="small hidden-phone">
                            <?php
                            if($item->updated_by){
                                ?>
                                <a class="hasTooltip" href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . (int) $item->updated_by); ?>" title="<?php echo JText::_('JAUTHOR'); ?>">
                                <?php echo $this->escape($item->updated_by_name); ?></a>
                                <?php
                            }
                            else echo '&nbsp;';
                            ?>
						</td>
						<td class="nowrap small hidden-phone">
							<?php 
                            if($item->updated_by) echo JHtml::_('date', $item->updated_at, JText::_('COM_INGALLERY_DATE_FORMAT'));
                            else echo '&nbsp;';
                            ?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif;?>

		<?php echo $this->pagination->getListFooter(); ?>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
