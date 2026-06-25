@option @option_skip_new_objects
Feature: The "skip new objects" option prevents creating not-yet-existing objects

  Background:
    Given there is a opendxp class "Product"
    And the definition has a input field "name"
    And there is a import-definition "Product" for definition
    And the import-definitions skips new objects
    And the import-definitions provider is "csv" with the configuration:
      | key        | value |
      | csvExample | name  |
      | delimiter  | ,     |
      | enclosure  | "     |
    And the import-definitions mapping is:
      | fromColumn | toColumn | primary |
      | name       | name     | true    |

  Scenario: Rows that match no existing object are skipped
    Given there is a file test.csv with content:
      """
      name,
      new1,
      new2
      """
    And I run the import-definitions with params:
      | key  | value    |
      | file | test.csv |
    Then there should be "0" data-objects for definition
