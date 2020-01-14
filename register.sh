#!/bin/bash

if [[ -z "${MOODLE_WWW}" ]]; then
	echo -e "ERROR: MOODLE_WWW not found in your environment !!!"
	exit -1
fi

if [[ ! -f "${MOODLE_WWW}/config.php" ]]; then
  echo -e "WARNING: 'config.php' not found on '${MOODLE_WWW}'. Moodle might be not initialized! "
  exit 0
fi

if grep -o 'questionbankcolumns' "${MOODLE_WWW}/config.php" ; then
	echo -e "\n NOTICE: 'qbank-tag-filter' already registered."
else	
	echo -e "# qbank-tag-filter \n\$CFG->questionbankcolumns = 'checkbox_column,question_type_column,question_name_column,edit_action_column,preview_action_column,copy_action_column,delete_action_column,creator_name_column,modifier_name_column,local_questionbanktagfilter_question_bank_column';" >> ${MOODLE_WWW}/config.php
	echo -e "Registering the 'qbank-tag-filter' form.... done."
fi

