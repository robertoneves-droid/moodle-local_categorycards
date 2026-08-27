<?php
namespace local_categorycards\privacy;

defined('MOODLE_INTERNAL') || die();

/**
 * Privacy Subsystem for local_categorycards.
 *
 * @package    local_categorycards
 * @copyright  2026 Roberto Neves
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements \core_privacy\local\metadata\null_provider {

    /**
     * Get the language string identifier explaining why this plugin stores no data.
     *
     * @return string
     */
    public static function get_reason() : string {
        return 'privacy:metadata';
    }
}
