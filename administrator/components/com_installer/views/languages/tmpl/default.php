<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_installer
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.multiselect');
JHtml::_('bootstrap.tooltip');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));

// Add spindle-wheel for language installation.
JFactory::getDocument()->addScriptDeclaration('
jQuery(document).ready(function($) {
	Joomla.loadingLayer("load");
	$("#adminForm").on("submit", function(e) {
		if (document.getElementsByName("task")[0].value == "languages.install")
		{
			Joomla.loadingLayer("show");
		}
	});
});
');
?>
<div id="installer-languages" class="clearfix">
	<form action="<?php echo JRoute::_('index.php?option=com_installer&view=languages'); ?>" method="post" name="adminForm" id="adminForm">
		<div id="j-main-container" class="j-main-container">
			<?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this, 'options' => array('filterButton' => false))); ?>
			<div class="clearfix"></div>
			<?php if (empty($this->items)) : ?>
			<div class="alert alert-warning alert-no-items">
				<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
			</div>
			<?php else : ?>
			<table class="table table-striped">
				<thead>
					<tr>
						<th width="1%" class="nowrap text-xs-center">
							<?php echo JHtml::_('grid.checkall'); ?>
						</th>
						<th class="nowrap">
							<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_LANGUAGE', 'name', $listDirn, $listOrder); ?>
						</th>
						<th width="10%" class="nowrap text-xs-center">
							<?php echo JHtml::_('searchtools.sort', 'COM_INSTALLER_HEADING_LANGUAGE_TAG', 'element', $listDirn, $listOrder); ?>
						</th>
						<th width="15%" class="text-xs-center">
							<?php echo JText::_('JVERSION'); ?>
						</th>
						<th width="35%" class="nowrap hidden-sm-down">
							<?php echo JText::_('COM_INSTALLER_HEADING_DETAILS_URL'); ?>
						</th>
						<th width="5%" class="nowrap hidden-sm-down text-xs-center">
							<?php echo JHtml::_('searchtools.sort', 'COM_INSTALLER_HEADING_ID', 'update_id', $listDirn, $listOrder); ?>
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="6">
							<?php echo $this->pagination->getListFooter(); ?>
						</td>
					</tr>
				</tfoot>
				<tbody>
				<?php
				$version = new JVersion;
				$currentShortVersion = preg_replace('#^([0-9\.]+)(|.*)$#', '$1', $version->getShortVersion());
				foreach ($this->items as $i => $language) :
					// Get language code and language image.
					preg_match('#^pkg_([a-z]{2,3}-[A-Z]{2})$#', $language->element, $element);
					$language->code  = $element[1];
					?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="text-xs-center">
							<?php echo JHtml::_('grid.id', $i, $language->update_id, false, 'cid'); ?>
						</td>
						<td>
							<label for="cb<?php echo $i; ?>">
								<?php echo $language->name; ?>
							</label>
						</td>
						<td class="text-xs-center">
							<?php echo $language->code; ?>
						</td>
						<td class="text-xs-center">
								<?php // Display a Note if language pack version is not equal to Joomla version ?>
								<?php if (substr($language->version, 0, 3) != $version::RELEASE || substr($language->version, 0, 5) != $currentShortVersion) : ?>
									<span class="tag tag-warning hasTooltip" title="<?php echo JText::_('JGLOBAL_LANGUAGE_VERSION_NOT_PLATFORM'); ?>"><?php echo $language->version; ?></span>
								<?php else : ?>
									<span class="tag tag-success"><?php echo $language->version; ?></span>
								<?php endif; ?>
						</td>
						<td class="small hidden-sm-down">
							<a href="<?php echo $language->detailsurl; ?>" target="_blank"><?php echo $language->detailsurl; ?></a>
						</td>
						<td class="hidden-sm-down text-xs-center">
							<?php echo $language->update_id; ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
			<?php endif; ?>
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>
