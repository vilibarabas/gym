<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the private 'sonata.core.flashmessage.twig.runtime' shared service.

include_once $this->targetDirs[3].'/vendor/sonata-project/core-bundle/src/Twig/Extension/FlashMessageRuntime.php';

return $this->services['sonata.core.flashmessage.twig.runtime'] = new \Sonata\Twig\Extension\FlashMessageRuntime(${($_ = isset($this->services['sonata.core.flashmessage.manager']) ? $this->services['sonata.core.flashmessage.manager'] : $this->load('getSonata_Core_Flashmessage_ManagerService.php')) && false ?: '_'});