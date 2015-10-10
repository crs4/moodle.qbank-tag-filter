<?php

defined('MOODLE_INTERNAL') || die;

global $CFG;
require_once($CFG->dirroot . '/question/editlib.php');


class local_questionbanktagfilter_question_bank_column extends \core_question\bank\column_base
{
    public function get_name()
    {
        return 'local_questionbanktagfilter|tags';
    }

    protected function get_title()
    {
        return 'Tags';
    }

    protected function display_content($question, $rowclasses)
    {
        echo $question->tagname;
    }


    public function get_extra_joins()
    {
        return array('tags' => 'LEFT JOIN {tag_instance} tagi ON tagi.itemid = q.id LEFT JOIN {tag} tag ON tag.id = tagi.tagid');
    }


    public function get_required_fields()
    {
        return array("tag.name AS tagname");
    }

    /**
     * Can this column be sorted on? You can return either:
     *  + false for no (the default),
     *  + a field name, if sorting this column corresponds to sorting on that datbase field.
     *  + an array of subnames to sort on as follows
     *  return array(
     *      'firstname' => array('field' => 'uc.firstname', 'title' => get_string('firstname')),
     *      'lastname' => array('field' => 'uc.lastname', 'field' => get_string('lastname')),
     *  );
     * As well as field, and field, you can also add 'revers' => 1 if you want the default sort
     * order to be DESC.
     * @return mixed as above.
     */
    public function is_sortable()
    {
        return 'tag.name';
    }
}

/*

    public function display_header() {
        // echo "displaying header for name: " . $this->get_name() . "title: " . $this->get_title() . ". Sortable: " . $this->is_sortable() . "<br />\n";
        echo '<th class="header ' . $this->get_classes() . '" scope="col">';
        $sortable = $this->is_sortable();
        $name = $this->get_name();
        $title = $this->get_title();
        $tip = $this->get_title_tip();
        if (is_array($sortable)) {
            if ($title) {
                echo '<div class="title">' . $title . '</div>';
            }
            $links = array();
            foreach ($sortable as $subsort => $details) {
                $links[] = $this->make_sort_link($name . '_' . $subsort,
                        $details['title'], '', !empty($details['reverse']));
            }
            echo '<div class="sorters">' . implode(' / ', $links) . '</div>';
        } else if ($sortable) {
            // echo $this->make_sort_link($name, $title, $tip);
           echo $this->make_sort_link($sortable, $title, $tip);
        } else {
            if ($tip) {
                echo '<span title="' . $tip . '">';
            }
            echo $title;
            if ($tip) {
                echo '</span>';
            }
        }
        echo "</th>\n";
    }
*/

