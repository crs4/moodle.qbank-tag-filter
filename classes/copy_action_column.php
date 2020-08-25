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
require_once('qbank_action_column.php');


/**
 * Copy action column for the QuestionBank table.
 *
 * @package    local
 * @subpackage questionbanktagfilter
 * @copyright  2015-2016 CRS4
 * @license    https://opensource.org/licenses/mit-license.php MIT license
 */
class local_questionbanktagfilter_copy_action_column extends local_questionbanktagfilter_qbank_action_column
{
    /**
     * Return the column name
     * @return string
     */
    public function get_name()
    {
        return 'local_questionbanktagfilter|copy';
    }


    /**
     * Column definition
     *
     * @param object $question
     * @param string $rowclasses
     */
    protected function display_content($question, $rowclasses)
    {
        global $USER;
        if ($question->qtype === 'omeromultichoice' || $question->qtype === 'omerointeractive') {
            $context = context_course::instance(required_param('courseid', PARAM_INT));
            if ($this->check_is_author($question, $USER) ||
                has_capability('question/qtype_omerocommon:author', $context, $USER)) {
                $this->print_icon('t/copy', $this->strcopy, $this->qbank->copy_question_url($question->id));
            }
        } else if (question_has_capability_on($question, 'add') &&
            (question_has_capability_on($question, 'edit') || question_has_capability_on($question, 'view'))) {
            $this->print_icon('t/copy', $this->strcopy, $this->qbank->copy_question_url($question->id));
        }
    }
}
