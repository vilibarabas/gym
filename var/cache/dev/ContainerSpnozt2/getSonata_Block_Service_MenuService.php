<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the public 'sonata.block.service.menu' shared service.

include_once $this->targetDirs[3].'/vendor/sonata-project/block-bundle/src/Block/BlockServiceInterface.php';
include_once $this->targetDirs[3].'/vendor/sonata-project/block-bundle/src/Block/Service/BlockServiceInterface.php';
include_once $this->targetDirs[3].'/vendor/sonata-project/block-bundle/src/Block/Service/AbstractBlockService.php';
include_once $this->targetDirs[3].'/vendor/sonata-project/block-bundle/src/Block/Service/AdminBlockServiceInterface.php';
include_once $this->targetDirs[3].'/vendor/sonata-project/block-bundle/src/Block/Service/AbstractAdminBlockService.php';
include_once $this->targetDirs[3].'/vendor/sonata-project/block-bundle/src/Block/Service/MenuBlockService.php';

return $this->services['sonata.block.service.menu'] = new \Sonata\BlockBundle\Block\Service\MenuBlockService('sonata.block.menu', ${($_ = isset($this->services['sonata.templating']) ? $this->services['sonata.templating'] : $this->load('getSonata_TemplatingService.php')) && false ?: '_'}, ${($_ = isset($this->services['knp_menu.menu_provider']) ? $this->services['knp_menu.menu_provider'] : $this->getKnpMenu_MenuProviderService()) && false ?: '_'}, ${($_ = isset($this->services['sonata.block.menu.registry']) ? $this->services['sonata.block.menu.registry'] : $this->load('getSonata_Block_Menu_RegistryService.php')) && false ?: '_'});
