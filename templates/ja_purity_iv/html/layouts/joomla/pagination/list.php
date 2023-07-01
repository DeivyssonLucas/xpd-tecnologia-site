<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

use Joomla\CMS\Language\Text;

$list = $displayData['list'];

?>
<nav role="navigation" aria-label="<?php echo Text::_('JLIB_HTML_PAGINATION'); ?>">
	<ul class="pagination">
		<?php foreach ($list['pages'] as $page) : ?>
			<?php echo $page['data']; ?>
		<?php endforeach; ?>
	</ul>
</nav>
