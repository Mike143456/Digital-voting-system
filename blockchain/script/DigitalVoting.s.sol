// SPDX-License-Identifier: UNLICENSED
pragma solidity ^0.8.13;

import "forge-std/Script.sol";
import "../src/DigitalVoting.sol";

contract DeployDigitalVoting is Script {
    function run() external {
        vm.startBroadcast();

        DigitalVoting voting = new DigitalVoting();

        console.log("DigitalVoting deployed to:", address(voting));

        vm.stopBroadcast();
    }
}
