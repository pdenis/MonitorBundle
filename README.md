SnideMonitorBundle
==================

Symfony 2 monitoring bundle based on Test class

[![Build Status](https://travis-ci.org/pdenis/MonitorBundle.png?branch=master)](https://travis-ci.org/pdenis/MonitorBundle)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/pdenis/MonitorBundle/badges/quality-score.png?s=db430d0b51814554d04706e8790a6642ae4322f3)](https://scrutinizer-ci.com/g/pdenis/MonitorBundle/)

## features
- Test class based
- Application management & chaining (via json exposition)
- Dashboard

## Installation

### Installation by Composer

If you use composer, add MonitorBundle bundle as a dependency to the composer.json of your application

```php
    "require": {
        ...
        "snide/monitor-bundle": "dev-master"
        ...
    },

```

Add SnideMonitorBundle to your application kernel.

```php
// app/AppKernel.php
<?php
    // ...
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Snide\MonitorBundle\SnideMonitorBundle(),
        );
    }
```

The bundle needs to copy the resources necessary to the web folder. You can use the command below:

```bash
    php app/console assets:install
```

## Overview

### Dashboard
<img src="https://raw.github.com/pdenis/MonitorBundle/master/docs/screenshots/monitor_dashboard.jpg" alt="Dashboard">

### Applications list
<img src="https://raw.github.com/pdenis/MonitorBundle/master/docs/screenshots/monitor_applications.jpg" alt="Applications list">

### Application tests
<img src="https://raw.github.com/pdenis/MonitorBundle/master/docs/screenshots/monitor_applications_tests.jpg" alt="Application test">

## Define your test service

To define tests services, add "snide_monitor.test" tag like this :

```xml
<service id="acme_demo.redis" class="Snide\Monitoring\Test\Redis" public="false">
    <tag name="snide_monitor.test" />
    <argument>Redis local instance</argument>
    <argument>127.0.0.1</argument>
    <argument>6379</argument>
</service>
```
## Chaining APP

You can add application reference & define its api URL (Example : /dashboard.json).
Your application now monitor other applications & tests appear on your dashboard.

## Full configuration

```yaml
    snide_monitor:
        timer: XX # Dashboard will be refreshed every XX seconds
        repository:
            type: yaml # only Yaml type is defined
            application:
                filename: /path/to/your/yaml/save/file.yml
```