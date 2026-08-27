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
 * Edit category cards settings page.
 *
 * @package    local_categorycards
 * @copyright  2026 Moodle
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/local/categorycards/lib.php');
require_once($CFG->libdir . '/filelib.php');

// Retrieve category ID parameter.
$categoryid = required_param('id', PARAM_INT);

// Verify category and context exists.
$category = $DB->get_record('course_categories', ['id' => $categoryid], '*', MUST_EXIST);
$context = context_coursecat::instance($categoryid);

// Verify permissions.
require_login();
require_capability('moodle/category:manage', $context);

// Page configuration.
$PAGE->set_url(new moodle_url('/local/categorycards/edit.php', ['id' => $categoryid]));
$PAGE->set_context($context);
$PAGE->set_title(get_string('categorycards_header', 'local_categorycards'));
$PAGE->set_heading($category->name);

// Setup navigation breadcrumbs.
$PAGE->navbar->add(get_string('categorycards_header', 'local_categorycards'));

$options = [
    'subdirs' => 0,
    'maxbytes' => 1024 * 1024 * 2, // 2MB.
    'maxfiles' => 1,
    'accepted_types' => ['image'],
];

// Retrieve existing settings if available.
$record = $DB->get_record('local_categorycards', ['categoryid' => $categoryid]);

// Instantiate the edit form using namespaced class.
$form = new local_categorycards\edit_form(null, ['categoryid' => $categoryid, 'context' => $context]);

// Process form action.
if ($form->is_cancelled()) {
    redirect(new moodle_url('/course/index.php', ['categoryid' => $categoryid]));
} else if ($data = $form->get_data()) {
    // Save/update record.
    if (!$record) {
        $record = new stdClass();
        $record->categoryid = $categoryid;
        $record->bgcolor = $data->bgcolor;
        $record->fontcolor = $data->fontcolor;
        $DB->insert_record('local_categorycards', $record);
    } else {
        $record->bgcolor = $data->bgcolor;
        $record->fontcolor = $data->fontcolor;
        $DB->update_record('local_categorycards', $record);
    }

    // Save uploaded cover image.
    file_save_draft_area_files(
        $data->cardimage,
        $context->id,
        'local_categorycards',
        'cardimage',
        0,
        $options
    );

    // Redirect to category view page with success status.
    redirect(
        new moodle_url('/course/index.php', ['categoryid' => $categoryid]),
        get_string('changessaved'),
        null,
        \core\output\notification::NOTIFY_SUCCESS
    );
} else {
    // Prepare form data and files draft area only on initial GET request.
    $formdata = new stdClass();
    $formdata->id = $categoryid;
    $formdata->bgcolor = $record ? $record->bgcolor : '#0f5b9e';
    $formdata->fontcolor = $record ? $record->fontcolor : '#ffffff';

    $draftitemid = 0;
    file_prepare_draft_area(
        $draftitemid,
        $context->id,
        'local_categorycards',
        'cardimage',
        0,
        $options
    );
    $formdata->cardimage = $draftitemid;

    $form->set_data($formdata);
}

// Display page output.
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('categorycards_header', 'local_categorycards'));
$form->display();
echo $OUTPUT->footer();
