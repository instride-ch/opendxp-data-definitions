@provider @provider_json
Feature: Importing data using the JSON provider

  Background:
    Given there is a opendxp class "Product"
    And the definition has a input field "name"
    And there is a import-definition "Product" for definition
    And the import-definitions provider is "json" with the configuration:
      | key         | value           |
      | jsonExample | [{"name":"x"}]  |
    And the import-definitions mapping is:
      | fromColumn | toColumn | primary |
      | name       | name     | true    |

  Scenario: Two objects are created from a JSON file
    Given there is a file test.json with content:
      """
      [{"name":"test1"},{"name":"test2"}]
      """
    And I run the import-definitions with params:
      | key  | value     |
      | file | .github/ci/files/test_data/test.json |
    Then there should be "2" data-objects for definition
