<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the public 'sonata.admin.builder.filter.factory' shared service.

include_once $this->targetDirs[3].'/vendor/sonata-project/admin-bundle/src/Filter/FilterFactoryInterface.php';
include_once $this->targetDirs[3].'/vendor/sonata-project/admin-bundle/src/Filter/FilterFactory.php';

return $this->services['sonata.admin.builder.filter.factory'] = new \Sonata\AdminBundle\Filter\FilterFactory($this, array());
