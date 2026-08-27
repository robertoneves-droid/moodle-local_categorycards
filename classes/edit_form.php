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
 * Edit form for category cards.
 *
 * @package    local_categorycards
 * @copyright  2026 Moodle
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_categorycards;

global $CFG;
require_once($CFG->libdir . '/formslib.php');

/**
 * Form for editing category cards settings.
 *
 * @package    local_categorycards
 * @copyright  2026 Moodle
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class edit_form extends \moodleform {

    /**
     * Define the form elements.
     */
    protected function definition() {
        $mform = $this->_form;

        $categoryid = $this->_customdata['categoryid'];
        $context = $this->_customdata['context'];

        // Header.
        $mform->addElement('header', 'categorycards_header', get_string('categorycards_header', 'local_categorycards'));

        // Color picker group for card background.
        $bgcolorpickerhtml = '<input type="color" id="id_bgcolor_picker" '
            . 'style="width:45px; height:36px; padding:0; border:1px solid #ced4da; '
            . 'border-radius:4px; cursor:pointer; vertical-align:middle; margin-left:10px;">';
        $bgcolorgroup = [
            $mform->createElement('text', 'bgcolor', '', ['id' => 'id_bgcolor', 'size' => 10]),
            $mform->createElement('html', $bgcolorpickerhtml),
        ];
        $mform->addGroup($bgcolorgroup, 'bgcolor_group', get_string('bgcolor', 'local_categorycards'), ' ', false);
        $mform->setType('bgcolor', PARAM_TEXT);
        $mform->setDefault('bgcolor', '#0f5b9e');
        $mform->addHelpButton('bgcolor_group', 'bgcolor', 'local_categorycards');

        // Color picker group for text font color.
        $fontcolorpickerhtml = '<input type="color" id="id_fontcolor_picker" '
            . 'style="width:45px; height:36px; padding:0; border:1px solid #ced4da; '
            . 'border-radius:4px; cursor:pointer; vertical-align:middle; margin-left:10px;">';
        $fontcolorgroup = [
            $mform->createElement('text', 'fontcolor', '', ['id' => 'id_fontcolor', 'size' => 10]),
            $mform->createElement('html', $fontcolorpickerhtml),
        ];
        $mform->addGroup($fontcolorgroup, 'fontcolor_group', get_string('fontcolor', 'local_categorycards'), ' ', false);
        $mform->setType('fontcolor', PARAM_TEXT);
        $mform->setDefault('fontcolor', '#ffffff');
        $mform->addHelpButton('fontcolor_group', 'fontcolor', 'local_categorycards');

        // Add Javascript to sync color box with text inputs.
        $mform->addElement('html', '
            <script>
            document.addEventListener("DOMContentLoaded", function() {
                var syncColor = function(inputId, pickerId) {
                    var input = document.getElementById(inputId);
                    var picker = document.getElementById(pickerId);
                    if (input && picker) {
                        picker.value = input.value;
                        input.addEventListener("input", function() {
                            if (/^#[0-9A-F]{6}$/i.test(input.value)) {
                                picker.value = input.value;
                            }
                        });
                        picker.addEventListener("input", function() {
                            input.value = picker.value;
                        });
                    }
                };
                syncColor("id_bgcolor", "id_bgcolor_picker");
                syncColor("id_fontcolor", "id_fontcolor_picker");
            });
            </script>
        ');

        // Image filemanager.
        $options = [
            'subdirs' => 0,
            'maxbytes' => 1024 * 1024 * 2, // 2MB.
            'maxfiles' => 1,
            'accepted_types' => ['image'],
        ];
        $mform->addElement('filemanager', 'cardimage', get_string('cardimage', 'local_categorycards'), null, $options);
        $mform->addHelpButton('cardimage', 'cardimage', 'local_categorycards');

        // Hidden input for category ID.
        $mform->addElement('hidden', 'id', $categoryid);
        $mform->setType('id', PARAM_INT);

        // Action buttons (Save/Cancel).
        $this->add_action_buttons(true, get_string('savechanges'));
    }
}
