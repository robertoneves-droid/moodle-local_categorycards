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

namespace local_categorycards;

/**
 * Hook callbacks for local_categorycards.
 *
 * @package    local_categorycards
 * @copyright  2026 Roberto Neves
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class hook_callbacks {

    /**
     * Callback for core\hook\output\before_footer_html_generation.
     *
     * Injects category cards metadata and AMD module on category listing pages.
     *
     * @param \core\hook\output\before_footer_html_generation|null $hook Hook instance.
     */
    public static function before_footer_html_generation(?\core\hook\output\before_footer_html_generation $hook = null): void {
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
}
