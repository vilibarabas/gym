<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the public 'sonata.block.service.text' shared service.

include_once $this->targetDirs[3].'/vendor/sonata-project/block-bundle/src/Block/BlockServiceInterface.php';
include_once $this->targetDirs[3].'/vendor/sonata-project/block-bundle/src/Block/Service/BlockServiceInterface.php';
include_once $this->targetDirs[3].'/vendor/sonata-project/block-bundle/src/Block/Service/AbstractBlockService.php';
include_once $this->targetDirs[3].'/vendor/sonata-project/block-bundle/src/Block/Service/AdminBlockServiceInterface.php';
include_once $this->targetDirs[3].'/vendor/sonata-project/block-bundle/src/Block/Service/AbstractAdminBlockService.php';
include_once $this->targetDirs[3].'/vendor/sonata-project/block-bundle/src/Block/Service/TextBlockService.php';

return $this->services['sonata.block.service.text'] = new \Sonata\BlockBundle\Block\Service\TextBlockService('sonata.block.text', ${($_ = isset($this->services['sonata.templating']) ? $this->services['sonata.templating'] : $this->load('getSonata_TemplatingService.php')) && false ?: '_'});
