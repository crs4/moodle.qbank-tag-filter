<?php

// Copyright (c) 2015-2016, CRS4
//
// Permission is hereby granted, free of charge, to any person obtaining a copy of
// this software and associated documentation files (the "Software"), to deal in
// the Software without restriction, including without limitation the rights to
// use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
// the Software, and to permit persons to whom the Software is furnished to do so,
// subject to the following conditions:
//
// The above copyright notice and this permission notice shall be included in all
// copies or substantial portions of the Software.
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
// FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
// COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
// IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
// CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

defined('MOODLE_INTERNAL') || die;

global $CFG;
require_once($CFG->dirroot . '/question/editlib.php');


/**
 * Base class for question bank columns that just contain an action icon.
 *
 * @copyright  2009 Tim Hunt
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class local_questionbanktagfilter_edit_action_column extends \core_question\bank\column_base
{
    protected $stredit;
    protected $strview;

    public function init()
    {
        parent::init();
        $this->stredit = get_string("edit", "local_questionbanktagfilter");
        $this->strtranslate = get_string("translate", "local_questionbanktagfilter");
        $this->strview = get_string("view", "local_questionbanktagfilter");
    }


    /**
     * Return the column title
     *
     * @return string
     */

    protected function get_title()
    {
        return '&#160;';
    }

    public function get_extra_classes()
    {
        return array('iconcol');
    }

    /**
     * Return the name of the filter column
     * @return string
     */
    public function get_name()
    {
        return 'local_questionbanktagfilter|edit';
    }


    protected function display_content($question, $rowclasses)
    {
        global $USER;
        if ($question->qtype === 'omeromultichoice' || $question->qtype === 'omerointeractive') {
            // Add "author edit" and "translate" function
            $context = context_course::instance(required_param('courseid', PARAM_INT));

            if (has_capability('question/qtype_omerocommon:author', $context, $USER) && question_has_capability_on($question, 'edit')) {
                $this->print_icon('t/edit', $this->stredit, $this->qbank->edit_question_url($question->id));
            }

            if (has_capability('question/qtype_omerocommon:translate', $context, $USER)) {
                $this->print_icon('i/publish', $this->strtranslate, $this->qbank->edit_question_url($question->id));
            }

            if (question_has_capability_on($question, 'view')) {
                $this->print_icon('i/info', $this->strview, $this->qbank->edit_question_url($question->id));
            }

        } else {
            // Default edit action for question not in {omeromultichoice, omerointeractive}
            if (question_has_capability_on($question, 'edit')) {
                $this->print_icon('t/edit', $this->stredit, $this->qbank->edit_question_url($question->id));
            } else if (question_has_capability_on($question, 'view')) {
                $this->print_icon('i/info', $this->strview, $this->qbank->edit_question_url($question->id));
            }
        }

    }

    private function edit_question_url($question, $mode = null)
    {
        return $this->qbank->edit_question_url($question->id) . (!is_null($mode) ? "&mode=$mode" : "");
    }

    
    protected function print_icon($icon, $title, $url)
    {
        global $OUTPUT;
        echo '<a title="' . $title . '" href="' . $url . '">
                <img src="' . $OUTPUT->pix_url($icon) . '" class="iconsmall" alt="' . $title . '" /></a>';
    }


    public function get_extra_joins()
    {
        return array();
    }

    public function get_required_fields()
    {
        return array();
    }
}
