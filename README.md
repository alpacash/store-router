# Magento 2 Store Router

This package will allow you to maintain specific rules based on http request conditions. We can add additional rules or conditions to this module whenever they are required.

## Configuration yaml file
The configuration file is located your project's root and named `.store-router.app.yaml`.

### Example configuration file

```yaml
mywebsite:
  rules:
    mage_run_code: 'mywebsite'

mywebsite-prod:
  inherit: mywebsite
  conditions:
    hosts:
      '*.mywebsite.com': '*'

mywebsite-staging:
  inherit: mywebsite
  conditions:
    hosts:
      'staging.mywebsite.com': '*'
  rules:
    auth: 'user:pass'
```

### Configuration groups
In the example configuration file above, the groups are named “mywebsite”, “mywebsite-prod”, and “mywebsite-stagin”. Every group has it's own unique identifier.

### Inherit another group's data
It's possible to inherit another group's data and merge or override it where necessary. Like we did in the example above, we can create one group which holds the rule for the `mage_run_code` and inherit the rules in every other group to avoid maintenance of duplicate content.

### Configuration group conditions
For now, the the request hostname will be the only possible type of conditions we have. This concludes the items from the  `conditions`  field in the example configuration file above. We can add additional conditions by extending  `src/Config/ConditionCollection.php` .

#### Configuration condition: hosts
The hostnames are matched using the `fnmatch()` function, meaning we can use wildcards. So in order to apply a certain rule for every subdomain of `example.com`, we can use `*.example.com` in the configuration as shown in the example above.

The value for every hostname in the configuration should be the request paths the rules should be applied for. The request path can be either a string or an array. Here's an example:
```yaml
# Multiple paths
'*.example.com':
    - 'nl-be'
    - 'de-de'
# Wildcard, meaning the rules 
# should apply to every request path
'*.example.uk': '*'
```

### Configuration group rules
Our rules are defined in the yaml configuration file under the `rules` field for every condition group. These can be inherited as well. We can implement additional rules by adding a key to the `ruleMapping` field in `src/Config/RuleCollection.php`, having the responsible `RuleContract` class name as it's value. For example, see `src/Rule/BasicAuth.php`.

#### Configuration rule: mage_run_code
We can define the MAGE_RUN_CODE property to persist a specific Magento store code and make sure to always load a specific Magento store, based on our defined http conditions. This way we can make sure to always display the `example_uk` store whenever someone visits `//example.com/uk`.

#### Configuration rule: auth
Based on http conditions, we can define specific http basic auth credentials in order to block access to the public. This is especially required for staging environments. The example below shows us how to define http basic auth credentials for `staging.example.com`, while maintaining public access for the production environment `example.com`.

```yaml
'example':
    'conditions':
        'hosts':
            'example.com': '*'

'staging.example.com':
    'conditions':
        'hosts':
            'staging.example.com': '*'
    'rules':
        'auth': 'user:pass'
```

##### IP address whitelisting
It is possible to whitelist a range of ip-addresses and allow their access without authentication. Add the whitelisted CIDR ranges to `app/etc/ip-whitelist/<group-id>`, one per line. Note that it is possible to comment out and disable whitelist ranges using `#`.

**Example:**
```
127.0.0.1/24
8.8.8.8/16
# 8.8.4.4/16
```
