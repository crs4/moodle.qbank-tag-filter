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
 * Definition of the QuestionBank column.
 *
 * @package    local
 * @subpackage questionbanktagfilter
 * @copyright  2015-2016 CRS4
 * @license    https://opensource.org/licenses/mit-license.php MIT license
 */
class local_questionbanktagfilter_question_bank_column extends \core_question\bank\column_base
{
    protected function get_classes()
    {
        $classes = $this->get_extra_classes();
        $classes[] = get_class($this);
        return implode(' ', $classes);
    }

    /**
     * Return the name of the filter column
     * @return string
     */
    public function get_name()
    {
        return 'local_questionbanktagfilter|tags';
    }

    /**
     * Return the column title
     *
     * @return string
     */
    protected function get_title()
    {
        return get_string("column_title", 'local_questionbanktagfilter');
    }

    /**
     * Print the tags related to a question as a string of comma separated tokens.
     */
    protected function display_content($question, $rowclasses)
    {
        echo implode(", ", $this->get_question_tags($question->id));
    }

    public function get_extra_joins()
    {
        return array();
    }

    public function get_required_fields()
    {
        return array();
    }

    /**
     * Return the list of tags related to a given question.
     *
     * @param $questionid
     * @param string $sortorder
     * @return string
     */
    protected function get_question_tags($questionid, $sortorder = 'name ASC')
    {
        global $DB;
        $values = $DB->get_records_sql("
            SELECT DISTINCT t.id as id, t.name AS name
              FROM {tag} t JOIN {tag_instance} ti ON t.id=ti.tagid
                           JOIN {question} q ON q.id=ti.itemid
              WHERE q.id=$questionid
          ORDER BY $sortorder");

        $options = array();
        foreach ($values as $name => $value) {
            $options[] = $value->name;
        }

        return $options;
    }
}
