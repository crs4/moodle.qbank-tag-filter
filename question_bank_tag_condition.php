<?php


defined('MOODLE_INTERNAL') || die();


require_once($CFG->dirroot . '/question/classes/bank/search/condition.php');

/**
 * Created by PhpStorm.
 * User: kikkomep
 * Date: 10/9/15
 * Time: 2:39 PM
 */
class question_bank_tag_condition extends \core_question\bank\search\condition
{
    /** @var bool Whether to include old "deleted" questions. */
    protected $hide;

    /** @var string SQL fragment to add to the where clause. */
    protected $where;

    /** @var string SQL fragment to add to the params to the WHERE clause. */
    protected $params;

    /**
     * Constructor.
     * @param bool $hide whether to include old "deleted" questions.
     */
    public function __construct($hide = true) {
        global $CFG, $DB;
        $this->hide = $hide;
        if ($hide) {
            $this->where = 'q.hidden = 0';
        }

        $tagidtest = null;
        $this->params = array();

        if(isset($_REQUEST["tag"])) {
            $current_tag = $_REQUEST["tag"];
            if (!empty($current_tag) && $current_tag !== "all") {
                list($tagidtest, $this->params) = $DB->get_in_or_equal($_REQUEST["tag"], SQL_PARAMS_NAMED, 'tag');
                $this->where = 'tag.id ' . $tagidtest;
            }
        }
    }

    public function where() {
        return  $this->where;
    }

    public function params() {
        return $this->params;
    }




    protected function get_tag_options($sortorder = 'name ASC') {
        global $DB;
        $values = $DB->get_records_sql("
            SELECT DISTINCT t.id as id, t.name AS name
              FROM {tag} t JOIN {tag_instance} ti ON t.id=ti.tagid
          ORDER BY $sortorder");

        $options = array("all" => "all tags");
        foreach($values as $name=>$value){
            $options[$value->id] = $value->name;
        }

        return $options;
    }


    public function display_options() {
        echo \html_writer::start_div('choosetag', array("style"=> "margin: 20px 0px;"));
        $current_tag = isset($_REQUEST['tag']) ? $_REQUEST['tag'] : "all";
        $tagmenu = $this->get_tag_options();
        echo \html_writer::label(get_string('selectonetag', 'local_questionbanktagfilter'), 'id_selectatag');
        echo \html_writer::select($tagmenu, 'tag', $current_tag, array(), array(
            'onchange' => "console.log(this); ",
            'class' => 'searchoptions',
            'id' => 'id_selectatag'
        ));

        echo '<script type="text/javascript">document.getElementById("id_selectatag").onchange=';
        echo 'function(){ document.getElementById("displayoptions").submit() }';
        echo '</script>';
        echo \html_writer::end_div() . "\n";
    }

    /**
     * Print HTML to display the "Also show old questions" checkbox
     */
    public function display_options_adv() {
        echo \html_writer::start_div();
        echo \html_writer::end_div() . "\n";
    }

}
