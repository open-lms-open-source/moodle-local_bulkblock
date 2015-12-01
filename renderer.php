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

defined('MOODLE_INTERNAL') || die;

class local_bulkblock_renderer extends plugin_renderer_base {
    public function render_form_page($mform) {
        $o = $this->output->header();
        $o .= $mform->render();
        $o .= $this->output->footer();
        return $o;
    }

    public function confirmation($data, $yesurl, $nourl) {
        $message = get_string('confirmaddtocourses', 'local_bulkblock', $data);
        $o = $this->output->header();
        $o .= $this->output->confirm($message, $yesurl, $nourl);
        $o .= $this->output->footer();
        return $o;
    }

    public function success_page($block, $count, $categoryid) {
        $o = $this->output->header();
        $data = (object)array('block' => get_string('pluginname', 'block_'.$block), 'count' => $count);
        $o .= html_writer::span(get_string('successmsg', 'local_bulkblock', $data));
        $returnurl = new \moodle_url('/course/index.php', array('categoryid' => $categoryid));
        $o .= html_writer::empty_tag('br');
        $o .= html_writer::link($returnurl, get_string('returntocategory', 'local_bulkblock'));
        $o .= $this->output->footer();
        return $o;
    }
}
