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
 * Event observers implementation for local_categorycards.
 *
 * @package    local_categorycards
 * @copyright  2026 Moodle
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_categorycards;

/**
 * Event observer class for local_categorycards.
 *
 * @package    local_categorycards
 * @copyright  2026 Moodle
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class observer {

    /**
     * Triggered when a category is deleted.
     *
     * @param \core\event\course_category_deleted $event
     */
    public static function category_deleted(\core\event\course_category_deleted $event) {
        global $DB;
        $categoryid = $event->objectid;

        // Delete associated card configuration record.
        $DB->delete_records('local_categorycards', ['categoryid' => $categoryid]);

        // Delete associated cover image files.
        $fs = get_file_storage();
        try {
            $context = \context_coursecat::instance($categoryid);
            $fs->delete_area_files($context->id, 'local_categorycards', 'cardimage', 0);
        } catch (\dml_exception $e) {
            // Context might already be deleted or not exist.
            unset($e);
        }
    }
}
