<?php

/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\NotificationBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SonataNotificationExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('core.xml');
        $loader->load('doctrine_orm.xml');
        $loader->load('handlers.xml');

        $container->setAlias('sonata.notification.iterator', $config['iterator']);
        $container->setAlias('sonata.notification.producer', $config['producer']);

        $container->getDefinition('sonata.notification.consumer.swift_mailer')
            ->replaceArgument(0, $config['handlers']['swift_mailer']['path'])
        ;

        $this->registerDoctrineMapping($config);
        $this->registerParameters($container, $config);
     }

     /**
      * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
      * @param $config
      * @return void
      */
    public function registerParameters(ContainerBuilder $container, $config)
    {
        $container->setParameter('sonata.notification.message.class', $config['class']['message']);
    }

     /**
     * @param array $config
     * @return void
     */
    public function registerDoctrineMapping(array $config)
    {

    }
}