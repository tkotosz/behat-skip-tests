<?php

namespace Bex\Behat\SkipTestsExtension\ServiceContainer;

use Behat\Testwork\ServiceContainer\Extension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * This class is the entry point of the extension
 *
 * @license http://opensource.org/licenses/MIT The MIT License
 */
class SkipTestsExtension implements Extension
{
    const CONFIG_KEY = 'skiptests';

     /**
     * {@inheritdoc}
     */
    public function getConfigKey()
    {
        return self::CONFIG_KEY;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        // nothing to do here
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(ExtensionManager $extensionManager)
    {
        // nothing to do here
    }

    /**
     * {@inheritdoc}
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        $builder
            ->children()
                ->booleanNode(Config::CONFIG_PARAM_SKIP_SCENARIOS)
                    ->defaultTrue()
                ->end()
                ->arrayNode(Config::CONFIG_PARAM_SKIP_TAGS)
                    ->defaultValue(['pending', 'skip'])
                    ->beforeNormalization()
                        ->always($this->getSkipTagsInitializer())
                    ->end()
                    ->prototype('scalar')->end()
                ->end()
            ->end();
    }

    /**
     * {@inheritdoc}
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/config'));
        $loader->load('services.xml');

        $extensionConfig = new Config($config);
        $container->set('bex.skip_tests_extension.config', $extensionConfig);
    }

    /**
     * @return \Closure
     */
    private function getSkipTagsInitializer()
    {
        return function ($value) {
            $value = empty($value) ? ['pending', 'skip'] : $value;
            return is_array($value) ? $value : [$value];
        };
    }
}
