<?php

namespace sharyn\JUnitFormatter;

use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Class: JUnitFormatterExtension
 *
 * @see ExtensionInterface
 */
class JUnitFormatterExtension implements ExtensionInterface
{
    const ENV_FILENAME = 'SHARYN_JUNIT_FILENAME';
    const ENV_OUTPUTDIR = 'SHARYN_JUNIT_OUTPUTDIR';
    const ENV_SUITE_NAME_PREFIX = 'SHARYN_JUNIT_SUITE_NAME_PREFIX';

    /**
     * process
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
    }

    /**
     * getConfigKey
     *
     * @return string
     */
    public function getConfigKey()
    {
        return "sharynjunit";
    }

    /**
     * initialize
     *
     * @param ExtensionManager $extensionManager
     */
    public function initialize(ExtensionManager $extensionManager)
    {
    }

    /**
     * configure
     *
     * @param ArrayNodeDefinition $builder
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        $builder->children()->scalarNode('filename')->defaultValue('test_report.xml');
        $builder->children()->scalarNode('outputDir')->defaultValue('build/tests');
        $builder->children()->scalarNode('suiteNamePrefix')->defaultValue('');
    }

    /**
     * load
     *
     * @param ContainerBuilder $container
     * @param array            $config
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $definition = new Definition('sharyn\\JUnitFormatter\\Formatter\\JUnitFormatter');

        if (!$filename = \getenv(self::ENV_FILENAME)) {
            $filename = $config['filename'];
        }

        if (!$outputDir = \getenv(self::ENV_OUTPUTDIR)) {
            $outputDir = $config['outputDir'];
        }

        if (!$suiteNamePrefix = \getenv(self::ENV_SUITE_NAME_PREFIX)) {
            $suiteNamePrefix = $config['suiteNamePrefix'];
        }

        $definition->addArgument($filename);
        $definition->addArgument($outputDir);
        $definition->addArgument($suiteNamePrefix);

        $container->setDefinition('junit.formatter', $definition)
            ->addTag('output.formatter');
    }
}
