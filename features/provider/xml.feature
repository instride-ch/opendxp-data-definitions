@provider @provider_xml
Feature: Importing data using the XML provider

  Background:
    Given there is a opendxp class "Product"
    And the definition has a input field "name"
    And there is a import-definition "Product" for definition
    And the import-definitions provider is "xml" with the configuration:
      | key   | value    |
      | xPath | //item   |
    And the import-definitions mapping is:
      | fromColumn | toColumn | primary |
      | name       | name     | true    |

  Scenario: Two objects are created from an XML file
    Given there is a file test.xml with content:
      """
      <items><item><name>test1</name></item><item><name>test2</name></item></items>
      """
    And I run the import-definitions with params:
      | key  | value    |
      | file | test.xml |
    Then there should be "2" data-objects for definition
