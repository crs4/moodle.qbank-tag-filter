# moodle.qbank-tag-filter

A tag based filter for Moodle question bank.


## How to install

* Copy this repository to the `<MOODLE-ROOT-DIR>/local/questionbanktagfilter`
* Go to *Site Administration* -> *Notifications* and follow the Moodle instructions to install the plugin
* Finally, add the following line to the file `<MOODLE-ROOT-DIR>/config.php`

        $CFG->questionbankcolumns = 'checkbox_column,question_type_column,question_name_column,edit_action_column,preview_action_column,copy_action_column,delete_action_column,creator_name_column,modifier_name_column,local_questionbanktagfilter_question_bank_column';