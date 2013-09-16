# Aura System

[![Build Status](https://travis-ci.org/auraphp/system.png?branch=develop)](https://travis-ci.org/auraphp/system)

The Aura System provides a full-stack Aura framework built around Aura library
packages.


## Getting Started

### Installation

To install via [Composer](http://getcomposer.org), issue the following command:

    composer create-project aura/system --stability=dev {$PROJECT_PATH} dev-develop

Alternatively, download the latest tarball from the
[downloads directory](http://auraphp.github.com/system/downloads) and
uncompress it to start your project.

### Check The Installation

Once you have installed the Aura system, start the built-in PHP server with an
Aura.Framework command:

    cd {$PROJECT_PATH}
    php package/Aura.Framework/cli/server

You can then open a browser and go to <http://0.0.0.0:8000> to see the
"Hello World!" demo output.

Press `Ctrl-C` to stop the built-in PHP server.

Additionally, you can run a command-line test:

    cd {$PROJECT_PATH}
    php package/Aura.Framework_Demo/cli/hello

You should see "Hello World!" as the output.

### Using A Real Web Server

To run Aura under Apache or another real web server, add a virtual host to
your web server, then point its document root to `{$PROJECT_PATH}/web`.

The `mod_rewrite` module should be installed. That will allow you to browse to
the virtual host without needing `index.php` in the URL.

### Remove the Framework_Demo Package

When you are satisifed that the installation is working, you can safely remove
the Aura.Framework_Demo package. If you installed via composer, edit composer.json file
to remove the package requirement and then run `composer update`. If you
installed via tarball, edit `config/_packages` to remove the package listing,
then remove the `package/Aura.Framework_Demo` directory.

### System Organization

The system directory structure is pretty straightforward:

    {$PROJECT}/
        config/                     # mode-specific config files
            _mode                   # The config mode, 'default' by default
            _packages               # Load these packages in order
            default.php             # default config overrides
            dev.php                 # shared development server
            local.php               # local (individual) development server
            prod.php                # production
            stage.php               # staging
            test.php                # testing
        include/                    # generic include-path directory
        package/                    # Aura packages
        tmp/                        # temporary files
        vendor/                     # Composer vendors
        web/                        # web server document root
            .htaccess               # mod_rewrite rules
            cache/                  # public cached files
            favicon.ico             # favicon to reduce error_log lines
            index.php               # bootstrap script


## Running Tests

For testing, you need to have [PHPUnit 3.7][phpunit] or later installed.

  [phpunit]: http://www.phpunit.de/manual/current/en/

To run the integration tests for the system as a whole, change to the `tests`
directory and issue `phpunit`:

    cd {$PROJECT_PATH}/tests
    phpunit

To run the unit tests for a package, change to that package's `tests`
directory and issue `phpunit`:

    cd {$PROJECT_PATH}/package/Aura.Autoload/tests
    phpunit


## Writing A Page Controller

Let's create a package and a page controller, and wire it up for browsing.
We will do so in a project-specific way, leaving out the complexities of
creating an independent package for distribution.

> Warning: If you have not removed the `Framework_Demo` package yet, please
> [do so](#remove-the-framework_demo-package) before continuing.  Otherwise,
> your routes will not work correctly.

### Create The Controller

Change to the `include/` directory and create a location for the example
package and a space for our first web page ...
    
    cd {$PROJECT_PATH}/include
    mkdir -p Example/Package/Web/Home
    cd Example/Package/Web/Home
    
... then create a file called `HomePage.php`. Add this code for a bare-bones
index action:

```php
<?php
namespace Example\Package\Web\Home;

use Aura\Framework\Web\Controller\AbstractPage;

class HomePage extends AbstractPage
{
    public function actionIndex()
    {
        $this->view = 'index';
    }
}
?>
```

### Create The View

Next, create a view for the index action in a file called `views/index.php`
and add the following code to it

```php
<?php echo "This is an example home page."; ?>
```

At this point your `include/` directory should look like this:

    include/
        Example
            Package/
                Web/
                    Home/
                        HomePage.php
                        views/
                            index.php

> N.b.: Technically you don't need a directory structure this deep. However,
> a structure like this makes it easy to add new pages as well as other
> support libraries without having to change the project organization later.


### Define Configuration

Now we need to wire up the page controller to the autoloader and the routing
system. Change to the system config directory:

    $ cd {$PROJECT_PATH}/config
    
Edit the `default.php` file and add this code at the end of the file:

```php
<?php
// attach the path for a route named 'home' to the controller and action
$di->params['Aura\Router\Map']['attach'][''] = [
    // all routes with the '' path prefix (i.e., no prefix)
    'routes' => [
        // a route named 'home'
        'home' => [
            'path' => '/',
            'values' => [
                'controller' => 'home',
                'action'     => 'index',
            ],
        ],
    ]
];

// map the 'home' controller value to the controller class
$di->params['Aura\Framework\Web\Controller\Factory']['map']['home'] = 'Example\Package\Web\Home\HomePage';
?>

### Try It Out

You should now be able to browse to the `/` URL to see "This is an example
home page."
