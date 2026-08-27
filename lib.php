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
 * Library functions for local_categorycards.
 *
 * @package    local_categorycards
 * @copyright  2026 Moodle
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Extends the category settings navigation to add the custom card settings tab.
 *
 * @param navigation_node $categorynode The navigation node for the category.
 * @param context_coursecat $catcontext The category context.
 */
function local_categorycards_extend_navigation_category_settings(\navigation_node $categorynode, \context_coursecat $catcontext) {
    if (has_capability('moodle/category:manage', $catcontext)) {
        $url = new moodle_url('/local/categorycards/edit.php', ['id' => $catcontext->instanceid]);
        $categorynode->add(
            get_string('categorycards_header', 'local_categorycards'),
            $url,
            navigation_node::TYPE_SETTING
        );
    }
}

/**
 * Serving files from local_categorycards file storage.
 *
 * @param stdClass $course course object
 * @param stdClass $cm course module object
 * @param context $context context object
 * @param string $filearea file area
 * @param array $args extra arguments
 * @param bool $forcedownload whether to force download
 * @param array $options additional options
 * @return bool false if file not found
 */
function local_categorycards_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {
    global $CFG;

    // Check context type.
    if ($context->contextlevel != CONTEXT_COURSECAT) {
        send_file_not_found();
    }

    // We only serve files under 'cardimage' filearea.
    if ($filearea !== 'cardimage') {
        send_file_not_found();
    }

    // Check permissions if needed (categories are usually public view).
    $fs = get_file_storage();

    // The first argument in Moodle pluginfile URLs is the itemid.
    $itemid = (int)array_shift($args);

    $filename = array_pop($args);
    $filepath = '/' . implode('/', $args) . '/';

    $file = $fs->get_file($context->id, 'local_categorycards', $filearea, $itemid, $filepath, $filename);
    if (!$file) {
        send_file_not_found();
    }

    send_stored_file($file, 0, 0, $forcedownload, $options);
}

/**
 * Hook to inject card styling and configuration on pages where categories are listed.
 */
function local_categorycards_before_footer() {
    global $PAGE, $DB;

    // Check if plugin is enabled globally.
    $enabled = get_config('local_categorycards', 'enabled');
    if ($enabled === '0' || $enabled === 0) {
        return;
    }

    // We target pages that list categories: frontpage, site homepage, and course category listings.
    $targetlayouts = ['frontpage', 'coursecat', 'site'];
    if (in_array($PAGE->pagelayout, $targetlayouts)) {
        $categoriesvisible = $DB->get_records_menu('course_categories', null, '', 'id, visible');
        $records = $DB->get_records('local_categorycards');
        $categorydata = [];

        foreach ($records as $record) {
            $context = \context_coursecat::instance($record->categoryid);
            $fs = get_file_storage();
            $files = $fs->get_area_files($context->id, 'local_categorycards', 'cardimage', 0, 'id, filepath, filename', false);
            $imageurl = null;

            if (!empty($files)) {
                foreach ($files as $file) {
                    if ($file->is_directory()) {
                        continue;
                    }
                    $url = \moodle_url::make_pluginfile_url(
                        $context->id,
                        'local_categorycards',
                        'cardimage',
                        0,
                        $file->get_filepath(),
                        $file->get_filename()
                    );
                    $imageurl = $url->out(false);
                    break;
                }
            }

            $visible = isset($categoriesvisible[$record->categoryid]) ? (int)$categoriesvisible[$record->categoryid] : 1;

            $categorydata[$record->categoryid] = [
                'bgcolor' => $record->bgcolor,
                'fontcolor' => $record->fontcolor,
                'imageurl' => $imageurl,
                'visible' => $visible,
            ];
        }

        // Retrieve column configuration.
        $columns = get_config('local_categorycards', 'columns') ?: 'auto';

        // Inject compiled AMD Javascript code.
        $PAGE->requires->js_call_amd('local_categorycards/cards', 'init', [$categorydata, $columns]);
    }
}
