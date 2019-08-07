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


defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/formslib.php');

class enrol_elediamultikeys_edit_form extends moodleform {

    function definition() {
        $mform = $this->_form;

        list($instance, $plugin, $context) = $this->_customdata;

        $mform->addElement('header', 'header', get_string('pluginname', 'enrol_elediamultikeys'));

        $mform->addElement('text', 'name', get_string('custominstancename', 'enrol'));
        $mform->setType('name', PARAM_RAW);

        $options = array(ENROL_INSTANCE_ENABLED  => get_string('yes'),
                         ENROL_INSTANCE_DISABLED => get_string('no'));
        $mform->addElement('select', 'status', get_string('status', 'enrol_elediamultikeys'), $options);
        $mform->addHelpButton('status', 'status', 'enrol_elediamultikeys');
        $mform->setDefault('status', $plugin->get_config('status'));

        if ($instance->id) {
            $roles = get_default_enrol_roles($context, $instance->roleid);
        } else {
            $roles = get_default_enrol_roles($context, $plugin->get_config('roleid'));
        }
        $mform->addElement('select', 'roleid', get_string('role', 'enrol_elediamultikeys'), $roles);
        $mform->setDefault('roleid', $plugin->get_config('roleid'));
        $mform->setType('roleid', PARAM_RAW);

        $mform->addElement('duration', 'enrolperiod', get_string('enrolperiod', 'enrol_elediamultikeys'), array('optional' => true, 'defaultunit' => 86400));
        $mform->setDefault('enrolperiod', $plugin->get_config('enrolperiod'));
        $mform->setType('enrolperiod', PARAM_RAW);

        $mform->addElement('date_selector', 'enrolstartdate', get_string('enrolstartdate', 'enrol_elediamultikeys'), array('optional' => true));
        $mform->setDefault('enrolstartdate', 0);
        $mform->setType('enrolstartdate', PARAM_RAW);

        $mform->addElement('date_selector', 'enrolenddate', get_string('enrolenddate', 'enrol_elediamultikeys'), array('optional' => true));
        $mform->setDefault('enrolenddate', 0);
        $mform->setType('enrolenddate', PARAM_RAW);

        $options = array(0 => get_string('never'),
                 1800 * 3600 * 24 => get_string('numdays', '', 1800),
                 1000 * 3600 * 24 => get_string('numdays', '', 1000),
                 365 * 3600 * 24 => get_string('numdays', '', 365),
                 180 * 3600 * 24 => get_string('numdays', '', 180),
                 150 * 3600 * 24 => get_string('numdays', '', 150),
                 120 * 3600 * 24 => get_string('numdays', '', 120),
                 90 * 3600 * 24 => get_string('numdays', '', 90),
                 60 * 3600 * 24 => get_string('numdays', '', 60),
                 30 * 3600 * 24 => get_string('numdays', '', 30),
                 21 * 3600 * 24 => get_string('numdays', '', 21),
                 14 * 3600 * 24 => get_string('numdays', '', 14),
                 7 * 3600 * 24 => get_string('numdays', '', 7));
        $mform->addElement('select', 'customint2', get_string('longtimenosee', 'enrol_elediamultikeys'), $options);
        $mform->setDefault('customint2', $plugin->get_config('longtimenosee'));
        $mform->setType('customint2', PARAM_INT);
        $mform->addHelpButton('customint2', 'longtimenosee', 'enrol_elediamultikeys');

        $mform->addElement('text', 'customint3', get_string('maxenrolled', 'enrol_elediamultikeys'));
        $mform->setDefault('customint3', $plugin->get_config('maxenrolled'));
        $mform->addHelpButton('customint3', 'maxenrolled', 'enrol_elediamultikeys');
        $mform->setType('customint3', PARAM_INT);

        $mform->addElement('advcheckbox', 'customint4', get_string('sendcoursewelcomemessage', 'enrol_elediamultikeys'));
        $mform->setDefault('customint4', $plugin->get_config('sendcoursewelcomemessage'));
        $mform->addHelpButton('customint4', 'sendcoursewelcomemessage', 'enrol_elediamultikeys');

        $mform->addElement('textarea', 'customtext1', get_string('customwelcomemessage', 'enrol_elediamultikeys'), array('cols'=>'60', 'rows'=>'8'));
        $mform->setType('customtext1', PARAM_RAW);

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
        $mform->addElement('hidden', 'courseid');
        $mform->setType('courseid', PARAM_INT);

        $this->add_action_buttons(true, ($instance->id ? null : get_string('addinstance', 'enrol')));

        $this->set_data($instance);
    }

    function validation($data, $files) {
        global $DB, $CFG;
        $errors = parent::validation($data, $files);

        list($instance, $plugin, $context) = $this->_customdata;
        $checkpassword = true;


        if ($data['status'] == ENROL_INSTANCE_ENABLED) {
            if (!empty($data['enrolenddate']) and $data['enrolenddate'] < $data['enrolstartdate']) {
                $errors['enrolenddate'] = get_string('enrolenddaterror', 'enrol_elediamultikeys');
            }
        }

        return $errors;
    }
}