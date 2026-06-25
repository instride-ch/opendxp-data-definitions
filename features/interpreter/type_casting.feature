@interpreter @interpreter_type_casting
Feature: Adding a import with a type casting interpreter
  The Interpreter casts the value to the configured type

  Background:
    Given there is a opendxp class "Product"
    And the definition has a input field "name"
    And the definition has a checkbox field "active"
    And there is a import-definition "Product" for definition
    And the import-definitions provider is "csv" with the configuration:
      | key         | value |
      | csvExample  | name  |
      | delimiter   | ,     |
      | enclosure   | "     |

  Scenario: When I run the import, the value is cast to a boolean
    Given the import-definitions mapping is:
      | fromColumn | toColumn | primary | interpreter  | interpreterConfig        |
      | name       | name     | true    |              |                          |
      | active     | active   | false   | type_casting | {"toType": "boolean"}    |
    And there is a file test.csv with content:
      """
      name,active
      test1,1
      """
    And I run the import-definitions with params:
      | key  | value    |
      | file | test.csv |
    Then there should be "1" data-objects for definition
    And the field "active" for object of the definition should have the value "true"
