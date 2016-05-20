@assignfeedback @assignfeedback_pdf @_only_local
Feature: Teachers can generate a response to a PDF submitted by a student
  In order to provide feedback to the student
  As a teacher
  I need to be able to generate a PDF

  @javascript
  Scenario: Student submits a PDF, the teacher generates a response and the student can then download the response
    Given the following "courses" exist:
      | fullname | shortname | category | groupmode |
      | Course 1 | C1 | 0 | 1 |
    And the following "users" exist:
      | username | firstname | lastname | email |
      | teacher1 | Teacher | 1 | teacher1@asd.com |
      | student1 | Student | 1 | student1@asd.com |
    And the following "course enrolments" exist:
      | user | course | role |
      | teacher1 | C1 | editingteacher |
      | student1 | C1 | student |
    And I log in as "teacher1"
    And I follow "Course 1"
    And I turn editing mode on
    And I add a "Assignment" to section "1" and I fill the form with:
      | Assignment name               | Test assignment name |
      | Description                   | Submit your PDF |
      | assignsubmission_pdf_enabled  | 1 |
      | assignsubmission_file_enabled | 0 |
      | assignfeedback_pdf_enabled    | 1 |
    And I log out
    And I log in as "student1"
    And I follow "Course 1"
    And I follow "Test assignment name"
    When I press "Add submission"
    And I upload "mod/assign/feedback/pdf/tests/pdf_test1.pdf" file to "PDF submissions" filemanager
    And I press "Save changes"
    Then I should see "Submitted for grading"
    And I should see "Download final submission"
    And I should see "Not graded"
    And I log out
    And I log in as "teacher1"
    And I follow "Course 1"
    And I follow "Test assignment name"
    And I follow "View all submissions"
    And I should see "Download final submission"
    And I click on "Grade" "link" in the "Student 1" "table_row"
    And I should see "Download final submission"
    And I should see "Annotate submission"
    And I follow "Annotate submission"
    And "div#everythingspinner" "css_element" should not exist
    And "div#everything" "css_element" should exist
    And "div#everything.hidden" "css_element" should not exist
    And I click on "input#generateresponse" "css_element"
    And I should see "Download response"
    And I should see "View response online"
    And I log out
    And I log in as "student1"
    And I follow "Course 1"
    And I follow "Test assignment name"
    And I should see "Download response"
    And I should see "View response online"