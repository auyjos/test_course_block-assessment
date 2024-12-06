<?php
defined('MOODLE_INTERNAL') || die();

class block_test_course extends block_base {
    private $headerhidden = true;
    private $show_users = true; // Mostrar u ocultar usuarios matriculados

    public function init() {
        $this->title = get_string('pluginname', 'block_test_course');
    }

    public function applicable_formats() {
        return array('all' => true, 'mod' => false, 'tag' => false, 'my' => false);
    }

    public function specialization() {
        if (strpos($this->page->pagetype, PAGE_COURSE_VIEW) === 0 && $this->page->course->id != SITEID) {
            $this->title = get_string('coursesummary', 'block_test_course');
            $this->headerhidden = false;
        }

        if (!empty($this->config->show_users)) {
            $this->show_users = $this->config->show_users;
        }
    }

    public function get_content() {
        global $CFG, $OUTPUT, $DB;

        if ($this->content !== NULL) {
            return $this->content;
        }

        if (empty($this->instance)) {
            return '';
        }

        $this->content = new stdClass();
        $options = new stdClass();
        $options->noclean = true;
        $options->overflowdiv = true;

        $context = context_course::instance($this->page->course->id);
        $this->page->course->summary = file_rewrite_pluginfile_urls(
            $this->page->course->summary,
            'pluginfile.php',
            $context->id,
            'course',
            'summary',
            NULL
        );

        $course_name = $this->page->course->fullname;
        $start_date = userdate($this->page->course->startdate);

        $users_list = '';
        if ($this->show_users) {
            $context = context_course::instance($this->page->course->id);
            $users = get_enrolled_users($context);

            foreach ($users as $user) {
                $users_list .= html_writer::tag('p', $user->firstname . ' ' . $user->lastname);
            }
        }

        $this->content->text = format_text($this->page->course->summary, $this->page->course->summaryformat, $options);
        $this->content->text .= html_writer::tag('p', get_string('course_name', 'block_test_course') . ': ' . $course_name);
        $this->content->text .= html_writer::tag('p', get_string('start_date', 'block_test_course') . ': ' . $start_date);
        $this->content->text .= html_writer::tag('p', get_string('users_list', 'block_test_course') . ': ' . $users_list);

        $this->content->footer = '';

        return $this->content;
    }

    public function hide_header() {
        return $this->headerhidden;
    }

    public function instance_config_save($data) {
        parent::instance_config_save($data);
        if (isset($data->show_users)) {
            $this->show_users = $data->show_users;
        }
    }
}
