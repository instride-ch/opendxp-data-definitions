@config @config_integrity
Feature: Service configuration integrity
  The service configuration must only reference classes that exist, so a rename
  or deletion can never silently break a conditionally loaded config (e.g. the
  ecommerce services, which the regular suite does not load).

  Scenario: Every form-type referenced in the service configuration exists
    Then every form-type referenced in the service configuration should exist

  Scenario: Every service class referenced in the service configuration exists
    Then every service class referenced in the service configuration should exist
