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
 * Grading method controller for the self-assessment plugin
 *
 * @package    gradingform_selfassessment
 * @copyright  2017 University of Helsinki
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/grade/grading/form/lib.php');

/**
 * This controller encapsulates the self-assessment grading logic
 *
 * @package    gradingform_selfassessment
 * @copyright  2017 University of Helsinki
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class gradingform_selfassessment_controller extends gradingform_controller {

    /**
     * Loads the submarker completed exercises if it exists.
     *
     */
    protected function load_definition() {
    }

    /**
     * Calculates and returns the possible minimum and maximum score (in points) for this self-assessment
     *
     * @return array
     */
    public function get_min_max_score() {
        $returnvalue['minscore'] += 0;
        $returnvalue['maxscore'] += 20;
        return $returnvalue;
    }

    /**
     * Returns the HTML code displaying the preview of the grading form
     *
     * @param moodle_page $page the target page
     * @return string
     */
    public function render_preview(moodle_page $page) {
        return "<div> render preview </div>";
    }

    /**
     * Deletes the guide definition and all the associated information
     */
    protected function delete_plugin_definition() {
        global $DB;
        }
}

/**
 * Class to manage one self-assessment grading instance.
 *
 * Stores information and performs actions like update, copy, validate, submit, etc.
 *
 * @package    gradingform_selfassessment
 * @copyright  2017 University of Helsinki
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class gradingform_selfassessment_instance extends gradingform_instance {

    /**
     * Updates the instance with the data received from grading form. This function may be
     * called via AJAX when grading is not yet completed, so it does not change the
     * status of the instance.
     *
     * @param array $data
     */
    public function update($data) {
        global $DB;
    }

    /**
     * Calculates the grade to be pushed to the gradebook
     *
     * @return float|int the valid grade from $this->get_controller()->get_grade_range()
     */
    public function get_grade() {
        return 0;
    }


    /**
     * Returns html for form element of type 'grading'.
     *
     * @param moodle_page $page
     * @param MoodleQuickForm_grading $gradingformelement
     * @return string
     */
    public function render_grading_element($page, $gradingformelement) {
        return "<div> render_grading_element </div>";
    }
}
