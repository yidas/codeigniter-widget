<p align="center">
    <a href="https://codeigniter.com/" target="_blank">
        <img src="https://codeigniter.com/assets/images/ci-logo-big.png" height="100px">
    </a>
    <h1 align="center">CodeIgniter Widget</h1>
    <br>
</p>

CodeIgniter 3 Widget for reusable building view blocks

[![Latest Stable Version](https://poser.pugx.org/yidas/codeigniter-widget/v/stable?format=flat-square)](https://packagist.org/packages/yidas/codeigniter-widget)
[![Latest Unstable Version](https://poser.pugx.org/yidas/codeigniter-widget/v/unstable?format=flat-square)](https://packagist.org/packages/yidas/codeigniter-widget)
[![License](https://poser.pugx.org/yidas/codeigniter-widget/license?format=flat-square)](https://packagist.org/packages/yidas/codeigniter-widget)

This Widget extension is collected into [yidas/codeigniter-pack](https://github.com/yidas/codeigniter-pack) which is a complete solution for Codeigniter framework.

Features
--------

- *Common interface with Yii2 pattern like*

- ***Reusable building blocks** implementation*

- ***PSR-4 Namespace** support for static call* 

---

OUTLINE
-------

- [Demonstration](#demonstration)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Create Widgets](#create-widgets)
  - [Rendering View](#rendering-view)
  - [Utilizing CodeIgniter Resources](#utilizing-codeigniter-resources)
- [Usage](#usage)
- [Reference](#reference)

---

DEMONSTRATION
-------------

Define a widget then use it into Codeigniter's view:

```html
<?php
use app\widgets\Hello;
?>
<div>
<?=Hello::widget(['message' => 'Good morning']);?>
</div>
```

---

REQUIREMENTS
------------
This library requires the following:

- PHP 5.4.0+
- CodeIgniter 3.0.0+
- yidas/codeigniter-psr4-autoload 1.0.0+

---

INSTALLATION
------------

Run Composer in your Codeigniter project under the folder `\application`:

    composer require yidas/codeigniter-widget
    
Check Codeigniter `application/config/config.php`:

```php
$config['composer_autoload'] = TRUE;
```
    
> You could customize the vendor path into `$config['composer_autoload']`

---

CONFIGURATION
-------------

Widget extension require [yidas/codeigniter-psr4-autoload](https://github.com/yidas/codeigniter-psr4-autoload) to implement PSR-4 Namespace, which you need to [configure](https://github.com/yidas/codeigniter-psr4-autoload#configuration) at first:


### 1. Enabling Hooks

The hooks feature can be globally enabled/disabled by setting the following item in the `application/config/config.php` file:

```php
$config['enable_hooks'] = TRUE;
```

### 2. Adding a Hook

Hooks are defined in the `application/config/hooks.php` file, add above hook into it:

```php
$hook['pre_system'][] = [new yidas\Psr4Autoload, 'register'];
```

After the configuration, you are able to create widgets.

---

CREATE WIDGETS
--------------

To create a widget, extend from `yidas\Widget` and override the `init()` and/or `run()` methods, remember to defind this widget a current namespace refering by [yidas/codeigniter-psr4-autoload](https://github.com/yidas/codeigniter-psr4-autoload).


`init()` contains the code that initializes the widget properties.

`run()` contains the code that generates the rendering result of the widget.

In the following example, `Hello` widget display the partial view with assigning to its `message` property. If the property is not set, it will display your Codeigniter `base_url` by default. This widget file should place in `application/widgets/Hello.php`:

```php
<?php

namespace app\widgets;

use yidas\Widget;

class Test extends Widget
{
    // Customized variable for Widget
    public $message;
    
    public function init()
    {
        // Use $this->CI for accessing Codeigniter object
        $baseUrl = $this->CI->config->item('base_url');
        
        if ($this->message === null) {
            $this->message = "Your Site: {$baseUrl}";
        }
    }
    
    public function run()
    {
        // Render the view `test.php` in `WidgetPath/views` directory,
        return $this->render('test', [
            'message' => $this->message,
            ]);
    }
}
```

> [yidas/codeigniter-psr4-autoload](https://github.com/yidas/codeigniter-psr4-autoload) provides the PSR-4 Namespace ability for Codeigniter framework.

### Rendering View

```php
public string render(string $view, array $variables=[])
```

By default, views for a widget should be stored in files in the `WidgetPath/views` directory, where WidgetPath stands for the directory containing the widget class file. 

Therefore, the above example will render the view file `app/widgets/views/hello.php`, assuming the widget class is located under `app/widgets`. 

You may override the `yidas\Widget::getViewPath()` method to customize the directory containing the widget view files.

*Example:*

```php
    public function run()
    {
        return $this->render('view_name', [
            'variable' => $this->property,
            ]);
    }
```


### Utilizing CodeIgniter Resources 

Widget already prepared `$CI` property which is CodeIgniter Resources object, you could access it by `$this->CI` in your widget's `init()` or `run()` methods.

```php
    public function run()
    {
        // Get data from a model
        $this->CI->load->model('Menu_model');
        $list = $this->CI->Menu_model->getList();
        
        // Build widget's view with list data
        return $this->render('test', [
            'list' => $list,
            ]);
    }
```

---

USAGE
-----

### Widget on View

Statically call `widget()` with config in view, and the widget would render into that place:

```html
<?php
use app\widgets\Hello;
?>
<div>
<?=Hello::widget(['message' => 'Good morning']);?>
</div>
```

---

REFERENCE
---------

- [Application Structure: Widgets | The Definitive Guide to Yii 2.0 | Yii PHP Framework](https://www.yiiframework.com/doc/guide/2.0/en/structure-widgets)
