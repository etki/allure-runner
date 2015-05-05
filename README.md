# WIP

This project is in active development, anything may change at any moment and
nothing is claimed to be working by now; documentation is mostly lying and
several dogs are required to launch this program. 

# Allure CLI runner

Hi there.

This microproject helps with automated Allure CLI runs. Since PHP is not such a
tight friend with CI and test-powered development as Java is, Allure report
generation will, probably, be mostly run on personal developers machines. This
repository provides PHP API to run Allure CLI tool and contains several test
framework plugins that will help in automating the process:

* [Codeception extension](#codeception-extension)
* [PHPUnit listener](#phpunit-listener)
* [Composer script handler](#composer-script-handler)

## Usage

### Common options

* `reportPath`: directory where report should appear. This is a mandatory
option.
* `sources`: list of source directories containing Allure report data. This is
a mandatory option as well.
* `reportVersion`: quite self-explanatory, `1.4.5` by default.
* `verbosity`: defines how verbose output may be. Allowed values: `debug`,
`notice`, `info`, `warning`, `error`, `mute` and `auto` (the last one leaves
runner to decide himself).
* `executable`: path to Allure executable (e.g. `allure`).
* `jar`: path to Allure `.jar` file. This differs from executable simply by
that executable will be called directly, while `.jar` will be called using
`java -jar`.
* `downloadMissingJar`: if set to true and both `executable` and `jar` options
don't provide valid executable, runner will try to automatically fetch latest
`.jar`. Please notice that runner won't try to install java itself.
* `throwOnMissingExecutable`: whether to throw exception or not on missing
executable. This will be set to true in direct API calls and to false in
framework plugins by default, though you can always override it.
* `throwOnNonZeroResult`: quite the same option that tells to throw exception
whenever Allure CLI returns something other than 0. This is set true by default
and to false in framework plugins. As of 

### Codeception extension

Codeception extension is enabled and configured as any other Codeception
extension:

```yml
# codeception.yml
extensions:
  enabled:
    - Etki\Shortcut\AllureRunner\Codeception
  config:
    Etki\Shortcut\AllureRunner\Codeception:
      reportPath: allure-report
      sources: allure-result
```

Please note that relatives paths are resolved relatively to directory, specified
as `log` in Codeception configuration (`tests/_output` by default). You can
traverse up using standard '..' pseudo directory name.

### PHPUnit listener

PHPUnit integration is a little bit difficult because of it's specialty:

```xml
<listeners>
  <listener class="Etki\Shortcut\AllureRunner\PHPUnit" file="/optional/path/to/MyListener.php">
    <arguments>
      <array>
        <element key="reportPath">
          <string>tests/reports/allure</string>
        </element>
        <element key="sources">
          <array>
            <element key="0">
              <string>tests/report-data</string>
            </element>
          </array>
        </element>
      </array>
    </arguments>
  </listener>
</listeners>
```

Internally, this class won't listen to anything but will launch runner during
it's own destruction (on script end).

### Composer script handler

Allure runner hooks into Composer just as any other package:

```json
{
  "scripts": {
    "test": [
      "Etki\\Shortcut\\AllureRunner\\Composer::generateReport"
    ]
  },
  "extra": {
    "allure-runner": {
      "sources": ["tests/report-data"],
      "reportPath": "tests/reports/allure"
    }
  }
}
```

All common options are available via `allure-runner` subsection of `extra`
section. All relative paths will be resolved relative to project root.

### To be done

* Atoum integration
* Behat integration
* PhpSpec integration
* Current testing hardcodes mocked classes FQCN. This is terribly bad.
* Classes sometimes are misplaced in their namespaces.
