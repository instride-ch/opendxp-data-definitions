@cleaner @cleaner_reference_cleaner
Feature: Adding an import with the reference cleaner

  Background:
    Given there is a opendxp class "Product"
    And the definition has a input field "name"
    And there is a import-definition "Product" for definition
    And the import-definitions cleaner is "reference_cleaner"
    And the import-definitions provider is "csv" with the configuration:
      | key        | value |
      | csvExample | name  |
      | delimiter  | ,     |
      | enclosure  | "     |
    And the import-definitions mapping is:
      | fromColumn | toColumn | primary |
      | name       | name     | true    |
    And there is a file test.csv with content:
      """
      name,
      test1,
      test2,
      test3
      """
    And I run the import-definitions with params:
      | key  | value    |
      | file | test.csv |

  Scenario: The import with the reference cleaner creates the objects
    Then there should be "3" data-objects for definition
