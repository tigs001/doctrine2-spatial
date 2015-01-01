doctrine2-spatial
=================

Some spatial data related classes developed for Doctrine 2 to be used in www.wantlet.com, modified to be used with Zend Framework 2.
All credit to the original author: @jhartikainen

Hopefully later, this will become a ZF2 module.

Implemented composer.json to allow for installation via composer.

Add the following to your composer.json

<pre><code>

    {
        "require": { "Wantlet": "dev-master" },
        "repositories": [
            {
                "type": "vcs",
                "url": "https://github.com/tigs001/doctrine2-spatial",
                "comment": "Doctrine Spatial Data Types"
            }
        ]
    }

</code></pre>

After installation, edit your doctrine configuration to include the following.

<pre><code>

    # Doctrine Configuration
    'doctrine' => array(
        'configuration' => array(
            'orm_default' => array(
                // Added for spacial types.
                'types' => array(
                    'point' => 'Wantlet\ORM\PointType'
                )
            )
        ),

        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    // <Your database connection parameters.>
                ),

                // This is so the schema tool will map our new type.
                'doctrine_type_mappings' => array(
                    'point' => 'point'
                )
            )
        )
    ),

</code></pre>
