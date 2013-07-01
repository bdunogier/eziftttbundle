# Installation

This extension is shipped as a Symfony 2 bundle. It depends on ezpublish, and therefore requires eZ Publish 5 to
be installed.

## Download

### Using composer

The bundle is registered on packagist. Just run `composer require bdunogier/eziftttbundle` from your ezpublish 5 root
folder. The only available version at the moment is "@dev" (master), but tags will be added at some point.

### Manually

Just check the extension out from github (`git clone git@github.com:bdunogier/eziftttbundle.git`), or download
the current dev version from https://github.com/bdunogier/eziftttbundle/archive/master.zip.

## Activation

First, add the bundle to your ezpublish/EzPublishKernel.php:

```php
class EzPublishKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // [...]
            new BD\Bundle\EzIFTTTBundle\BDEzIFTTTBundle()
        );


You also need to register the extension's routes by adding these lines to `ezpublish/config/routing.yml` file:
```

```yml
bd_ezifttt:
    resource: "@BDEzIFTTTBundle/Resources/config/routing.yml"
    prefix:   /
```

## IFTTT

The final step is to connect your IFTTT account with your site. Of course, it  also means that your site must be
accessible from the IFTTT servers.

* Log in to http://ifttt.com, and browse to channels.
* Click on the wordpress one (at the bottom).
* Click on activate
* In blog URL, enter your eZ Publish 5 site's URL
* Enter your eZ username & password

That should be it.

## Troubleshooting

If channel activation fails, IFTTT won't be very verbose about it. The easiest would be to send an HTTP request
to your server and check the response for errors. An example XML request is provided in the extension for this purpose,
`Resources/doc/xml-rpc/getRecentPosts.xml`.

You can send this request using CURL as follows:

```
curl --form "fileupload=@vendor/bdunogier/eziftttbundle/BD/Bundle/EzIFTTTBundle/Resources/doc/xml-rpc/getRecentPosts.xml" http://ezpublish5/xmlrpc.php
```