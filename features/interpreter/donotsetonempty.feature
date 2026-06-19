@interpreter @interpreter_donotsetonempty
Feature: Adding a import with a do-not-set-on-empty interpreter
  The Interpreter keeps the existing value when the imported value is empty

  Background:
    Given there is a opendxp class "Product"
    And the definition has a input field "name"
    And the definition has a input field "label"
    And there is a import-definition "Product" for definition
    And the import-definitions provider is "csv" with the configuration:
      | key         | value |
      | csvExample  | name  |
      | delimiter   | ,     |
      | enclosure   | "     |
    And the import-definitions mapping is:
      | fromColumn | toColumn | primary | interpreter      |
      | name       | name     | true    |                  |
      | label      | label    | false   | donotsetonempty  |
    And there is a file test.csv with content:
      """
      name,label
      test1,keep
      """
    And I run the import-definitions with params:
      | key  | value    |
      | file | test.csv |

  Scenario: Re-importing with an empty value keeps the existing value
    Given there is a file test.csv with content:
      """
      name,label
      test1,
      """
    And I run the import-definitions with params:
      | key  | value    |
      | file | test.csv |
    Then there should be "1" data-objects for definition
    And the field "label" for object of the definition should have the value "keep"
