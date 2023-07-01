<?php
/**
T4 Overide
 */

defined('_JEXEC') or die;

$title      = $item->anchor_title ? ' title="' . $item->anchor_title . '"' : '';
$anchor_css = $item->anchor_css ?: '';

$linktype   = $item->title;

if ($item->menu_image)
{
	$itemParams = version_compare('4','ge') ? $item->getParams() : $item->params;
	if ($item->menu_image_css)
	{
		$image_attributes['class'] = $item->menu_image_css;
		$linktype = JHtml::_('image', $item->menu_image, $item->title, $image_attributes);
	}
	else
	{
		$linktype = JHtml::_('image', $item->menu_image, $item->title);
	}

	if ($itemParams->get('menu_text', 1))
	{
		$linktype .= '<span class="image-title">' . $item->title . '</span>';
	}
}

if ($item->level > 1) {
	$anchor_css .= " dropdown-item";
} else {
	$anchor_css .= " nav-link";
}

$attributes = '';
$attributes .= ' itemprop="name" ';
$attrDrop = version_compare(JVERSION, '4.0', 'ge') ? 'data-bs-toggle="dropdown"' : 'data-toggle="dropdown"';

if($item->deeper){
	if(!$item->mega_sub){
		$anchor_css .= ' dropdown-toggle';
	}
	$attributes .= ' role="button" ';
	$attributes .= ' aria-haspopup="true"';
	$attributes .= ' aria-expanded="false"';
	$attributes .= ' '.$attrDrop;
}

$linktype = (!empty($item->icon)  ? $item->icon  : "") . $linktype . (!empty($item->caret)  ? $item->caret  : "") . (!empty($item->caption)  ? $item->caption  : "");

?>
<span class="separator <?php echo $anchor_css; ?>"<?php echo $title; ?> <?php echo $attributes; ?>><?php echo $linktype; ?></span>
