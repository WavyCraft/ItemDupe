<?php

declare(strict_types=1);

namespace itemdupe;

use pocketmine\plugin\PluginBase;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;

use pocketmine\block\ItemFrame;
use pocketmine\block\tile\ItemFrame as TileItemFrame;

class ItemDupe extends PluginBase implements Listener {

    protected function onEnable() : void{
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onPlayerInteract(PlayerInteractEvent $event) : void{
        $block = $event->getBlock();
        $player = $event->getPlayer();
        $item = $event->getItem();

        if(!$block instanceof ItemFrame){
            return;
        }

        $world = $block->getPosition()->getWorld();
        $tile = $world->getTile($block->getPosition());

        if(!$tile instanceof TileItemFrame){
            return;
        }

        if($tile->getItem()->isNull() && !$item->isNull()){
            $event->cancel();

            $tile->setItem(clone $item);
            $block->setFramedItem(clone $item);
            $world->setBlock($block->getPosition(), $block);
        }
    }
}