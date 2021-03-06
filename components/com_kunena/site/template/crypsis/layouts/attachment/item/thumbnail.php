<?php
/**
 * Kunena Component
* @package Kunena.Template.Crypsis
* @subpackage BBCode
*
* @copyright (C) 2008 - 2015 Kunena Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @link http://www.kunena.org
**/
defined ( '_JEXEC' ) or die ();

/** @var KunenaAttachment $attachment */
$attachment = $this->attachment;

$config = KunenaConfig::getInstance();

$attributesLink = $attachment->isImage() && $config->lightbox ? ' class="fancybox-button" rel="fancybox-button"' : '';
$attributesImg = ' style="max-height: '. (int) $config->thumbheight . 'px;"';
?>
<a href="<?php echo $attachment->getUrl(); ?>" title="<?php echo $attachment->getShortName(0,7); ?>"<?php echo $attributesLink; ?>>
	<img src="<?php echo $attachment->getUrl(true); ?>"<?php echo $attributesImg; ?> alt="" />
</a>
