@option @option_force_load_objects
Feature: The "force loads objects" option still imports objects

  Background:
    Given there is a opendxp class "Product"
    And the definition has a input field "name"
    And there is a import-definition "Product" for definition
    And the import-definitions force loads objects
    And the import-definitions provider is "csv" with the configuration:
      | key        | value |
      | csvExample | name  |
      | delimiter  | ,     |
      | enclosure  | "     |
    And the import-definitions mapping is:
      | fromColumn | toColumn | primary |
      | name       | name     | true    |

  Scenario: Objects are created with force-load enabled
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
