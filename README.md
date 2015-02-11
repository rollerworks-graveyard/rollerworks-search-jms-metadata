RollerworksSearch JMS Metadata extension
========================================

This package provides a compatible Metadata reader for [RollerworksSearch][1].

The Metadata reader uses the [JMS Metadata component][2] for loading Metadata
from e.g. an XML document, a YAML file or PHP Annotations.

RollerworksSearch is a powerful open-source Search system, if you are new to
RollerworksSearch, please read the documentation in the main repository
of [RollerworksSearch][1].

If you'd like to contribute to this project, please see the [RollerworksSearch contributing guide lines][3]
for more information.

Installation
------------

To install this extension, add the `search-jms-metadata` to your composer.json

```bash
$ php composer.phar require rollerworks/search-jms-metadata
```

Documentation
-------------

The bulk of the documentation is stored in the `doc/index.md`
file in this package:

[Read the Documentation for master][4]

License
-------

The source of this package is subject to the MIT license that is bundled
with this source code in the file [LICENSE](LICENSE).

The [JMS Metadata component][2] is subject to the Apache2.0 license.
And is incompatible with GNU GPLv2 and up. 

[1]: https://github.com/rollerworks/RollerworksSearch
[2]: https://github.com/schmittjoh/metadata
[3]: https://github.com/rollerworks/RollerworksSearch#contributing
[4]: https://github.com/rollerworks/rollerworks-jms-metadata/blob/master/doc/index.md
