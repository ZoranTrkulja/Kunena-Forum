<?php
/**
 * Kunena Component
 * @package Kunena.Administrator.Template
 * @subpackage Smilies
 *
 * @copyright (C) 2008 - 2012 Kunena Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.org
 **/
defined ( '_JEXEC' ) or die ();

$document = JFactory::getDocument();
$iconPath = json_encode(JUri::root(true) . '/');
$document->addScriptDeclaration("function update_smiley(newimage) {
	document.smiley_image.src = {$iconPath} + newimage;
}");
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

$changeOrder 	= ($this->state->get('list.ordering') == 'ordering' && $this->state->get('list.direction') == 'asc');

?>
	<div id="j-sidebar-container" class="span2">
		<div id="sidebar">
			<div class="sidebar-nav"><?php include KPATH_ADMIN.'/template/joomla30/common/menu.php'; ?></div>
		</div>
	</div>
	<div id="j-main-container" class="span10">

             <div class="well well-small" style="min-height:120px;">
                       <div class="nav-header"><?php if ( !$this->state->get('item.id') ): ?><?php echo JText::_('COM_KUNENA_EMOTICONS_NEW_SMILEY'); ?><?php else: ?><?php echo JText::_('COM_KUNENA_EMOTICONS_EDIT_SMILEY'); ?><?php endif; ?></div>
                         <div class="row-striped">
                         <br />
		<form action="<?php echo KunenaRoute::_('administrator/index.php?option=com_kunena') ?>" method="post" id="adminForm" name="adminForm">
			<input type="hidden" name="view" value="smilies" />
			<input type="hidden" name="task" value="save" />
			<input type="hidden" name="boxchecked" value="0" />
			<?php if ( $this->state->get('item.id') ): ?><input type="hidden" name="smileyid" value="<?php echo $this->state->get('item.id') ?>" /><?php endif; ?>
			<?php echo JHtml::_( 'form.token' ); ?>

			<table class="adminform">
				<tr align="center">
					<td width="100"><?php
					echo JText::_('COM_KUNENA_EMOTICONS_CODE');
					?></td>
					<td width="200"><input class="post" type="text" name="smiley_code"
						value="<?php echo isset($this->smiley_selected) ? $this->smiley_selected->code : '' ?>" /></td>
					<td rowspan="3" width="50"><img name="smiley_image"
						src="<?php echo isset($this->smiley_selected) ? $this->escape($this->ktemplate->getSmileyPath($this->smiley_selected->location, true)) : '' ?>" border="0" alt="" /> &nbsp;</td>
					<td rowspan="3">&nbsp;</td>
				</tr>
				<tr align="center">
					<td width="100"><?php
					echo JText::_('COM_KUNENA_EMOTICONS_URL');
					?></td>
					<td><?php echo $this->listsmileys?> &nbsp;
					</td>
				</tr>
				<tr>
					<td width="100"><?php
					echo JText::_('COM_KUNENA_EMOTICONS_EMOTICONBAR');
					?></td>
					<td><input type="checkbox" name="smiley_emoticonbar" value="1"
						<?php
					if ($this->state->get('item.id') && $this->smiley_selected->emoticonbar == 1) {
						echo 'checked="checked"';
					}
					?> /></td>
				</tr>
			</table>
		</form>
        </div>
        </div>

</div>

<div class="pull-right small">
	<?php echo KunenaVersion::getLongVersionHTML(); ?>
</div>