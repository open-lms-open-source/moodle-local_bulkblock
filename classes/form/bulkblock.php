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

namespace local_bulkblock\form;

require_once($CFG->libdir.'/formslib.php');
require_once($CFG->libdir.'/blocklib.php');

class bulkblock extends \moodleform {
    protected function definition() {
        global $PAGE;

        $mform = $this->_form;
        $context = $this->_customdata['context'];

        // Text search box.
        $mform->addElement('header', 'general', get_string('addbulkblock', 'local_bulkblock'));

        $blocks = \core_component::get_plugin_list('block');
        $options = array();
        foreach (array_keys($blocks) as $block) {
            $bi = block_instance($block);
            $formats = $bi->applicable_formats();
            if (!empty($formats['all']) || !empty($formats['course']) || !empty($formats['course-view'])) {
                if (!has_capability("block/$block:addinstance", $context)) {
                    continue;
                }
                $options[$block] = $bi->get_title();
            }
        }
        $mform->addElement('select', 'block', get_string('block'), $options);

        $mform->addElement('hidden', 'id', $this->_customdata['category']->id);
        $mform->hardFreeze('id');
        $mform->setType('id', PARAM_INT);

        $this->add_action_buttons(true, get_string('submit'));
    }
}
