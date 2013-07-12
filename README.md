# eZ IFTTT Bundle

## Author
Bertrand Dunogier (http://github.com/bdunogier)

## If This Then eZ Publish 5
This Symfony 2 bundle provides a connector for IFTTT, allowing you
to publish content to eZ Publish 5 (http://github.com/ezsystems/ezpublish-community)
from an http://IFTTT.com recipe.

Of course, IFTTT.com doesn't have native eZ Publish integration,
nor custom web hooks. But it is actually quite easy to re-use the Wordpress one, 
that relies on the Wordpress XML-RPC API.

## Current behaviour & usage
For now, it will create a new Folder (the default one, from plain site) at the Content root. It will of course be made more flexible.

## Configuration & usage
See INSTALL.md.

## Requirements
This bundle requires:
- ezpublish 5.1 / Community project >= 2013.06.0
- http://github.com/bdunogier/iftttbundle
  This bundle is a generic Symfony 2 implementation of the IFTTT wordpress action.

## Credits

Inspiration from https://github.com/captn3m0/ifttt-webhook.
