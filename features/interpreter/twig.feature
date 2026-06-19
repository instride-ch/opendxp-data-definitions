@interpreter @interpreter_twig
Feature: Adding a import with a twig interpreter
  The Interpreter will transform the value through a twig template

  Background:
    Given there is a opendxp class "Product"
    And the definition has a input field "name"
    And the definition has a input field "name2"
    And there is a import-definition "Product" for definition
    And the import-definitions provider is "csv" with the configuration:
      | key         | value |
      | csvExample  | name  |
      | delimiter   | ,     |
      | enclosure   | "     |

  Scenario: When I run the import, the value is rendered through the twig template
    Given the import-definitions mapping is:
      | fromColumn | toColumn | primary | interpreter | interpreterConfig            |
      | name       | name     | true    |             |                              |
      | name2      | name2    | false   | twig        | {"template": "{{ value }}_twig"} |
    And there is a file test.csv with content:
      """
      name,name2
      test1,hello
      """
    And I run the import-definitions with params:
      | key  | value    |
      | file | test.csv |
    Then there should be "1" data-objects for definition
    And the field "name2" for object of the definition should have the value "hello_twig"
