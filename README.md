# eZ IFTTT Bundle

## If This Then eZ Publish 5
This Symfony 2 bundle provides a connector for IFTTT, allowing you
to publish content to eZ Publish 5 (http://github.com/ezsystems/ezpublish-community)
from an http://IFTTT.com recipe.

Of course, IFTTT.com doesn't have native eZ Publish integration,
nor custom web hooks. But it is actually quite easy to re-use the Wordpress one, that relies on the Wordpress XML-RPC API.

## Installation
Drop the bundle in the src/BD/Bundles folder (or install through composer, but I haven't checked that yet) and enable it in your ezpublish/EzPublishKernel.php file.

## Current behaviour & usage
For now, it will create a new Folder (the default one, from plain site) at the Content root. It will of course be made more flexible.

## IFTTT.com configuration

Enable the Wordpress channel on your IFTTT.com account.
Use your site's URL, username and password.

The provided user & password must of course match a user 
who has permissions to create folders at the Content root. And that's pretty much it.

## Credits

Inspiration from https://github.com/captn3m0/ifttt-webhook.