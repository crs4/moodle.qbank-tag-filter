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
 * Base action column for the QuestionBank table.
 *
 * @package    local
 * @subpackage questionbanktagfilter
 * @copyright  2015-2016 CRS4
 * @license    https://opensource.org/licenses/mit-license.php MIT license
 */
abstract class local_questionbanktagfilter_qbank_action_column extends \core_question\bank\column_base
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

    /**
     * Specify extra classes for the column.
     * @return array
     */
    public function get_extra_classes()
    {
        return array('iconcol');
    }

    /**
     * Return a question record
     *
     * @param $question
     * @return mixed
     */
    protected function get_all_question_info($question)
    {
        global $DB;
        return $DB->get_record('question', array('id' => $question->id), "*", MUST_EXIST);
    }

    /**
     * Check whether the user if the author of the question.
     *
     * @param $question
     * @param null $user
     * @return bool
     */
    protected function check_is_author($question, $user = null)
    {
        global $USER, $DB;
        $created_by = isset($question->createdby) ? $question->createdby : null;
        if (is_null($created_by)) {
            $created_by = $this->get_all_question_info($question)->createdby;
        }
        $user = is_null($user) ? $USER : $user;
        return $user->id === $created_by;
    }

    /**
     * Prepare a URL for the edit action
     *
     * @param $question
     * @param string $mode
     * @return string
     */
    protected function edit_question_url($question, $mode = "author")
    {
        return $this->qbank->edit_question_url($question->id) . (!is_null($mode) ? "&mode=$mode" : "");
    }


    /**
     * Prepare HTML code to print the icon related to an action.
     *
     * @param $icon
     * @param $title
     * @param $url
     */
    protected function print_icon($icon, $title, $url)
    {
        global $OUTPUT;
        echo '<a title="' . $title . '" href="' . $url . '">
                <img src="' . $OUTPUT->pix_url($icon) . '" class="iconsmall" alt="' . $title . '" /></a>';
    }
}
