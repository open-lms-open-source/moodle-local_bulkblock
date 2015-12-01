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

namespace local_bulkblock;

class controller {
    protected $category;
    protected $renderer;

    public function __construct($category, $renderer) {
        $this->category = $category;
        $this->renderer = $renderer;
    }

    public function execute() {
        $action = optional_param('step', 'select', PARAM_ALPHA);
        if ($action != 'confirm') {
            $action = 'select';
        }
        $action = "action_$action";
        $this->$action();
    }

    protected function action_select() {
        global $PAGE;

        $cancelurl = new \moodle_url('/course/index.php', array('categoryid' => $this->category->id));

        $mform = new form\bulkblock(null, array('category' => $this->category, 'context' => $this->category->get_context()));
        if ($mform->is_cancelled()) {
            redirect($cancelurl);
        } else if ($data = $mform->get_data()) {
            $yesurl = new \moodle_url('/local/bulkblock/index.php',
                array('id' => $this->category->id, 'block' => $data->block, 'step' => 'confirm', 'sesskey' => sesskey()));
            $strdata = (object)array(
                'blockname' => get_string('pluginname', "block_{$data->block}"),
                'categoryname' => format_string($this->category->name),
                'coursecount' => $this->category->get_courses_count(),
            );
            echo $this->renderer->confirmation($strdata, $yesurl, $cancelurl);
            exit;
        }

        echo $this->renderer->render_form_page($mform);
    }

    protected function action_confirm() {
        global $CFG, $DB;
        require_once($CFG->libdir.'/blocklib.php');
        $block = optional_param('block', null, PARAM_COMPONENT);
        require_sesskey();

        $courses = $this->category->get_courses(array('recursive' => true));
        $blocks = blocks_parse_default_blocks_list($block);

        $count = 0;
        foreach ($courses as $course) {
            $context = \context_course::instance($course->id);
            if (!has_capability("block/$block:addinstance", $context)) {
                continue;
            }

            $page = new \moodle_page();
            $page->set_course($course);

            if (!$DB->record_exists('block_instances', array('blockname' => $block, 'parentcontextid' => $context->id))) {
                $page->blocks->add_blocks($blocks, 'course-view-*');
                $count++;
            }
        }
        echo $this->renderer->success_page($block, $count, $this->category->id);
    }
}
