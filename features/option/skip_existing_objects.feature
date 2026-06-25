@option @option_skip_existing_objects
Feature: The "skip existing objects" option leaves matched objects untouched

  Background:
    Given there is a opendxp class "Product"
    And the definition has a input field "name"
    And the definition has a input field "label"
    And there is a import-definition "Product" for definition
    And the import-definitions provider is "csv" with the configuration:
      | key        | value |
      | csvExample | name  |
      | delimiter  | ,     |
      | enclosure  | "     |
    And the import-definitions mapping is:
      | fromColumn | toColumn | primary |
      | name       | name     | true    |
      | label      | label    | false   |
    And there is a file test.csv with content:
      """
      name,label
      test1,first
      """
    And I run the import-definitions with params:
      | key  | value    |
      | file | test.csv |

  Scenario: An existing object is not updated when skipping existing objects
    Given the import-definitions skips existing objects
    And there is a file test.csv with content:
      """
      name,label
      test1,second
      """
    And I run the import-definitions with params:
      | key  | value    |
      | file | test.csv |
    Then there should be "1" data-objects for definition
    And the field "label" for object of the definition should have the value "first"
