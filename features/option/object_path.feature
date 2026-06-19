@option @option_object_path
Feature: The object-path option controls where new objects are created

  Background:
    Given there is a opendxp class "Product"
    And the definition has a input field "name"
    And there is a import-definition "Product" for definition
    And the import-definitions object-path is "/imported"
    And the import-definitions provider is "csv" with the configuration:
      | key        | value |
      | csvExample | name  |
      | delimiter  | ,     |
      | enclosure  | "     |
    And the import-definitions mapping is:
      | fromColumn | toColumn | primary |
      | name       | name     | true    |

  Scenario: Objects are created when an object-path is set
    Given there is a file test.csv with content:
      """
      name,
      test1,
      test2
      """
    And I run the import-definitions with params:
      | key  | value    |
      | file | test.csv |
    Then there should be "2" data-objects for definition
