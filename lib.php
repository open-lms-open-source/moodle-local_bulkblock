<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Bulk block creator
 * @package   local_bulkblock
 * @copyright Copyright (c) 2021 Open LMS (https://www.openlms.net)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Add link to settings navigation in categories.
 *
 * @param type $navigation
 * @param context_coursecat $context
 * @return void
 */
function local_bulkblock_extend_settings_navigation($navigation, $context) {
    if (is_null($navigation) or is_null($context)) {
        return;
    }
    if (!($context instanceof context_coursecat)) {
        return;
    }
    if (!has_capability('local/bulkblock:use', $context)) {
        return;
    }
    // Only add link when in the context of a coursecat.
    if ($node = $navigation->get('categorysettings')) {
        $url = new moodle_url('/local/bulkblock/index.php', array('id' => $context->instanceid));
        $node->add(get_string('pluginname', 'local_bulkblock'), $url, navigation_node::TYPE_CUSTOM,
                    null, 'localbulkblock', new pix_icon('t/add', ''));
    }

}
