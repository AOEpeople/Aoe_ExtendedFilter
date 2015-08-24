[![AOE](aoe-logo.png)](http://www.aoe.com)

# Aoe_ExtendedFilter Magento Module

## License
[OSL v3.0](http://opensource.org/licenses/OSL-3.0)

## Contributors
* [Lee Saferite](https://github.com/LeeSaferite) (AOE)

## Compatability
* Model Rewrites
    * core/email_template_filter
    * cms/template_filter
    * widget/template_filter
* Module Dependencies
    * Mage_Core
    * Mage_Cms
    * Mage_Widget

## Usage
This module extends the core, cms, and widget filter models to make adding new directives a simple process.
After installation you can add new directives with a simple addition to the config.xml of your module and a new model class.
The config.xml of this module includes two new directives, 'config' and 'translate', that are added using the new XML config.

    <config>
        <global>
            <filter>
                <directives>
                    <translate>Aoe_ExtendedFilter/Directive_Translate</translate>
                    <config>Aoe_ExtendedFilter/Directive_Config</config>
                </directives>
            </filter>
        </global>
    </config>

The element name for your directive is the name used to call the directive.
NB: Directive names are currently limited to 10 characters and limited to a-z. The regex pattern used is ```[a-z]{0,10}```.

    <translate>...</translate>
    {{translate ... }}

The new directive model class needs to implement the ```Aoe_ExtendedFilter_Model_Directive_Interface``` interface

    class Aoe_ExtendedFilter_Model_Directive_Translate implements Aoe_ExtendedFilter_Model_Directive_Interface

Usage of the new directives is identical to the existing directives.

    {{translate text="Hello World"}}
    {{config path="general/store_information/phone"}}

## Dev Notes
The $params array passed to the process() method on a directive is the result of a preg_match_all call and as such the 0 index is the full matched text, 1 is the directive name, and 2 is everything else.
The 2 index should be passed to the parameter parser in most, but not all, cases.
This will result in key/value pairs of data that were passed as arguments to the directive.
It will also resolve any template variables to their final value.
So, given this directive in template:

    {{translate text="Hello World"}}

Then the initial $params array passed to the process() method would be:

    $params[0] === 'translate text="Hello World"';
    $params[1] === 'translate';
    $params[2] === ' text="Hello World"';

After parsing with the following code:

    // Re-parse the third parameter with the tokenizer and discard original parameters
    $params = $filter->getIncludeParameters($params[2]);

You will get:

    $params['text'] === 'Hello World';
