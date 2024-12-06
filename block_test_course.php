<?php
// Prevent direct access to the file.
defined('MOODLE_INTERNAL') || die();

// Define the class for the block plugin.
class block_test_course extends block_base
{
    // Private properties for block configuration.
    private $headerhidden = true; // Indicates whether the block header is hidden.
    private $show_users = true; // Controls visibility of enrolled users.

    // Initialization function to set the block's title.
    public function init()
    {
        $this->title = get_string('pluginname', 'block_test_course'); // Fetch the block name from language strings.
    }

    // Define where this block can be added within Moodle.
    public function applicable_formats()
    {
        return array(
            'all' => true, // Block can be added to all pages.
            'mod' => false, // Block cannot be added to activity modules.
            'tag' => false, // Block cannot be added to tag pages.
            'my' => false, // Block cannot be added to the user's Dashboard.
        );
    }

    // Adjust the block's behavior based on the context or configuration.
    public function specialization()
    {
        // Check if the page is a course view and not the site front page.
        if (strpos($this->page->pagetype, PAGE_COURSE_VIEW) === 0 && $this->page->course->id != SITEID) {
            $this->title = get_string('coursesummary', 'block_test_course'); // Set a custom title for course pages.
            $this->headerhidden = false; // Show the header in course contexts.
        }

        // If the block has configuration for showing users, update the property.
        if (!empty($this->config->show_users)) {
            $this->show_users = $this->config->show_users;
        }
    }

    // Generate the content to be displayed in the block.
    public function get_content()
    {
        global $CFG, $OUTPUT, $DB; // Import global Moodle variables.

        // Return cached content if already generated.
        if ($this->content !== NULL) {
            return $this->content;
        }

        // Return an empty string if the block instance is not defined.
        if (empty($this->instance)) {
            return '';
        }

        // Initialize a new content object for the block.
        $this->content = new stdClass();
        $options = new stdClass(); // Options for text formatting.
        $options->noclean = true; // Allow raw HTML content.
        $options->overflowdiv = true; // Wrap content in an overflow div if necessary.

        // Get the course context.
        $context = context_course::instance($this->page->course->id);

        // Replace file URLs in the course summary with proper pluginfile URLs.
        $this->page->course->summary = file_rewrite_pluginfile_urls(
            $this->page->course->summary,
            'pluginfile.php',
            $context->id,
            'course',
            'summary',
            NULL
        );

        // Get the course name and start date.
        $course_name = $this->page->course->fullname;
        $start_date = userdate($this->page->course->startdate); // Format the date based on the user's preferences.

        // Generate a list of enrolled users if enabled in the configuration.
        $users_list = '';
        if ($this->show_users) {
            // Fetch the enrolled users in the course.
            $users = get_enrolled_users($context);

            // Loop through the users and build an HTML list of names.
            foreach ($users as $user) {
                $users_list .= html_writer::tag('p', $user->firstname . ' ' . $user->lastname);
            }
        }

        // Build the content to display in the block.
        $this->content->text = format_text($this->page->course->summary, $this->page->course->summaryformat, $options); // Add the course summary.
        $this->content->text .= html_writer::tag('p', get_string('course_name', 'block_test_course') . ': ' . $course_name); // Add the course name.
        $this->content->text .= html_writer::tag('p', get_string('start_date', 'block_test_course') . ': ' . $start_date); // Add the start date.
        $this->content->text .= html_writer::tag('p', get_string('users_list', 'block_test_course') . ': ' . $users_list); // Add the list of users if applicable.

        $this->content->footer = ''; // Leave the footer empty for now.

        return $this->content;
    }

    // Determine whether to hide the block's header.
    public function hide_header()
    {
        return $this->headerhidden; // Return the value of the header hidden property.
    }

    // Save the instance configuration and update block properties.
    public function instance_config_save($data)
    {
        parent::instance_config_save($data); // Call the parent class's method.

        // Update the show_users property if it exists in the configuration.
        if (isset($data->show_users)) {
            $this->show_users = $data->show_users;
        }
    }
}
