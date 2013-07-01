# eZ IFTTT Bundle

## Author
Bertrand Dunogier (bd at ez dot no), eZ Systems

## If This Then eZ Publish 5
This Symfony 2 bundle provides a connector for IFTTT, allowing you
to publish content to eZ Publish 5 (http://github.com/ezsystems/ezpublish-community)
from an http://IFTTT.com recipe.

Of course, IFTTT.com doesn't have native eZ Publish integration,
nor custom web hooks. But it is actually quite easy to re-use the Wordpress one, that relies on the Wordpress XML-RPC API.

## Current behaviour & usage
For now, it will create a new Folder (the default one, from plain site) at the Content root. It will of course be made more flexible.

## Configuration & usage
See INSTALL.md.

## Credits

Inspiration from https://github.com/captn3m0/ifttt-webhook.