<?php

/*
 * This file is part of the sfDependencyInjectionPlugin package.
 * (c) Issei Murasawa <issei.m7@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @author Issei Murasawa <issei.m7@gmail.com>
 */
class sfServiceConfigHandler extends sfYamlConfigHandler
{
    /**
     * {@inheritdoc}
     */
    public function execute($configFiles)
    {
        $class       = sfConfig::get('sf_container_class');
        $baseClass   = interface_exists('sfServiceContainerInterface') ? 'sfCompatibleWithSymfony15Container' : 'sfContainer';
        $date        = date('Y/m/d H:i:s');
        $debug       = var_export(sfConfig::get('sf_debug'), true);
        $configPaths = var_export($configFiles, true);

        return <<< EOF
<?php
// auto-generated by sfServiceConfigHandler
// date: $date

\$class = '$class';
if (!class_exists(\$class, false)) {
    \$path  = sfConfig::get('sf_app_cache_dir') . '/$class.php';
    \$cache = new Symfony\Component\Config\ConfigCache(\$path, $debug);
    if (!\$cache->isFresh()) {
        \$generator = new sfContainerGenerator($configPaths, $debug, sfEventDispatcherRetriever::retrieve(\$this));
        \$generator->generate(\$cache, '$baseClass');
    }

    require_once \$path;
}

return \$class;
EOF;
    }
}
