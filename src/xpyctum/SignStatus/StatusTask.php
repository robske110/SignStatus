<?php
namespace xpyctum\SignStatus;

use pocketmine\scheduler\Task;
use pocketmine\tile\Sign;

class StatusTask extends Task{
    private $plugin;

    public function __construct(SignStatus $plugin){
        $this->plugin = $plugin;
    }

    public function onRun(int $currentTick){
        $format = $this->plugin->format->getAll();
        foreach ($this->plugin->getServer()->getLevels() as $levels) {
            foreach ($levels->getTiles() as $tile) {
                if ($tile instanceof Sign) {
                    if (strtolower($tile->getText()[0]) == strtolower($this->plugin->format->getAll()["format"][1])) {
                        $tps = $this->plugin->getServer()->getTicksPerSecond();
                        $p = count($this->plugin->getServer()->getOnlinePlayers());
                        $full = $this->plugin->getServer()->getMaxPlayers();
                        $load = $this->plugin->getServer()->getTickUsage();
                        $level = $tile->getLevel()->getName();
                        $index = [];
                        for ($x = 0; $x <= 3; $x++) {
                            $v = $format["format"][$x + 1];
                            $v = str_replace("{ONLINE}", $p, $v);
                            $v = str_replace("{MAX_ONLINE}", $full, $v);
                            $v = str_replace("{WORLD_NAME}", $level, $v);
                            $v = str_replace("{TPS}", $tps, $v);
                            $v = str_replace("{SERVER_LOAD}", $load, $v);
                            $index[$x] = $v;
                        }
                        $tile->setText($index[0], $index[1], $index[2], $index[3]);
                    }
                }
            }
        }
    }
}
