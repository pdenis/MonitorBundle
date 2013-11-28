<?php

/*
 * This file is part of the SnideMonitorBundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Snide\Bundle\MonitorBundle;

use Snide\Bundle\MonitorBundle\DependencyInjection\Compiler\TestPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class SnideMonitorBundle
 *
 * @author Pascal DENIS <pascal.denis.75@gmail.com>
 */
class SnideMonitorBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new TestPass());
    }
}