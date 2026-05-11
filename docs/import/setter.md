## Setter
A Setter sets the data to the object as it would be needed.

 - Objectbrick -> saves the data to an objectbrick
 - Localizedfield -> saves the data to the specific language field
 - Classificationstore -> Saves the data to a classificationstore field
 - Fieldcollection -> Saves the data to a fieldcollection
 - Key -> Sets Object Key to a more dynamic value
 - ObjectType -> Sets Object Type to a more dynamic value

Of course, you can also implement your own Setters. Its basically the same as with Interpreters.

Todo that, you need to implement the interface ```Instride\Bundle\DataDefinitionsBundle\Setter\SetterInterface``` and create a service

```yml
acme_bundle.data_definitions.my_setter:
    class: AcmeBundle\DataDefinitions\MySetter
    tags:
      - { name: data_definitions.setter, type: mysetter, form-type: Instride\Bundle\DataDefinitionsBundle\Form\Type\NoConfigurationType }
```

If your Setter does have configuration as well, you need to create a new FormType and add a new Javascript file for the GUI:

```javascript
opendxp.registerNS('opendxp.plugin.datadefinitions.setters.mysetter');

opendxp.plugin.datadefinitions.setters.mysetter = Class.create(opendxp.plugin.datadefinitions.setters.abstract, {

});

```


You also need to load your Javascript File in your config.yml
```yml
data_definitionss:
  opendxp_admin:
    js:
      my_setter: '/static/opendxp/mysetter.js'
```
