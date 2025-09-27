// SPDX-License-Identifier: UNLICENSED
pragma solidity ^0.8.13;

import {Test, console} from "forge-std/Test.sol";
import {DigitalVoting} from "../src/DigitalVoting.sol";

// We need to redefine the structs so the test file recognizes them
struct PollStruct {
    uint id;
    string title;
    string desc;
    string image;
    uint voteCount;
    uint contestants;
    bool deleted;
    uint startsAt;
    uint endsAt;
    address creator;
    uint timeForCreatingPoll;
    address[] voters;
}

struct ContestantStruct {
    uint id;
    string name;
    string desc;
    string image;
    string partyName;
    uint votes;
}

contract DigitalVotingTest is Test {
    DigitalVoting voting;

    address public creator = address(0x1);
    address public voter1 = address(0x2);
    address public voter2 = address(0x3);

    function setUp() public {
        voting = new DigitalVoting();
    }

    function testCreatePoll() public {
        vm.prank(creator);
        voting.createPoll(
            "My First Poll",
            "image.url",
            "This is a test poll.",
            block.timestamp + 100, // Starts in 100 seconds
            block.timestamp + 200 // Ends in 200 seconds
        );

        DigitalVoting.PollStruct memory poll = voting.getAPoll(1);

        assertEq(poll.id, 1);
        assertEq(poll.creator, creator);
        assertEq(poll.voteCount, 0);
        assertEq(poll.contestants, 0);
        assertEq(poll.deleted, false);
        assertTrue(poll.startsAt > block.timestamp);
        assertEq(poll.voters.length, 0);
    }

    function testAddContestant() public {
        vm.prank(creator);
        voting.createPoll(
            "My Contestant Poll",
            "image.url",
            "This is a test poll.",
            block.timestamp + 100,
            block.timestamp + 200
        );

        vm.prank(creator);
        voting.addContestant(
            1,
            "Contestant A",
            "A popular candidate.",
            "imageA.url",
            "Party A"
        );

        DigitalVoting.ContestantStruct memory contestant = voting.getAContestant(1, 1);

        assertEq(contestant.id, 1);
        assertEq(contestant.votes, 0);
        assertEq(bytes(contestant.name), bytes("Contestant A"));

        // Add a second contestant
        vm.prank(creator);
        voting.addContestant(
            1,
            "Contestant B",
            "Another candidate.",
            "imageB.url",
            "Party B"
        );

        DigitalVoting.PollStruct memory poll = voting.getAPoll(1);
        assertEq(poll.contestants, 2);
    }

    function testAddDuplicateContestantFails() public {
        vm.prank(creator);
        voting.createPoll("Poll", "", "", block.timestamp + 100, block.timestamp + 200);

        vm.prank(creator);
        voting.addContestant(1, "John Doe", "desc", "img", "Party X");

        // Attempt to add a duplicate (same name and party)
        vm.prank(creator);
        vm.expectRevert("Contestant already exists for this poll");
        voting.addContestant(1, "John Doe", "desc", "img", "Party X");
    }

    function testVote() public {
        vm.prank(creator);
        voting.createPoll("Voting Test", "", "", block.timestamp + 10, block.timestamp + 100);

        vm.prank(creator);
        voting.addContestant(1, "Alice", "", "", "Party A");

        // Move time to after the start date
        vm.warp(block.timestamp + 50);

        vm.prank(voter1);
        voting.vote(1, 1);

        DigitalVoting.ContestantStruct memory contestant = voting.getAContestant(1, 1);
        assertEq(contestant.votes, 1);

        DigitalVoting.PollStruct memory poll = voting.getAPoll(1);
        assertEq(poll.voters.length, 1);
        assertEq(poll.voters[0], voter1);
    }

    function testVotingTwiceFails() public {
        vm.prank(creator);
        voting.createPoll("Voting Test 2", "", "", block.timestamp + 10, block.timestamp + 100);

        vm.prank(creator);
        voting.addContestant(1, "Bob", "", "", "Party B");

        vm.warp(block.timestamp + 50);

        vm.prank(voter1);
        voting.vote(1, 1);

        vm.prank(voter1);
        vm.expectRevert("Already voted!!!");
        voting.vote(1, 1);
    }

    function testUpdatePollFailsAfterStart() public {
        vm.prank(creator);
        voting.createPoll("Update Test", "", "", block.timestamp + 10, block.timestamp + 100);

        vm.warp(block.timestamp + 50);

        vm.prank(creator);
        vm.expectRevert("Poll has already started and cannot be updated");
        voting.updatePoll(1, "New Title", "", "");
    }
    
    function testDeletePoll() public {
        vm.prank(creator);
        voting.createPoll("Delete Test", "", "", block.timestamp + 100, block.timestamp + 200);
        
        vm.prank(creator);
        voting.deletePoll(1);
        
        // Check if the poll is marked as deleted
        DigitalVoting.PollStruct memory poll = voting.getAPoll(1);
        assertTrue(poll.deleted);
    }
    
    function testGetAllPolls() public {
        vm.prank(creator);
        voting.createPoll("Poll 1", "", "", block.timestamp + 100, block.timestamp + 200);
        voting.createPoll("Poll 2", "", "", block.timestamp + 100, block.timestamp + 200);
        
        DigitalVoting.PollStruct[] memory allPolls = voting.getAllPolls();
        assertEq(allPolls.length, 2);
        assertEq(allPolls[0].id, 1);
        assertEq(allPolls[1].id, 2);
    }

    function testGetAllContestants() public {
        vm.prank(creator);
        voting.createPoll("Contestant Getter Test", "", "", block.timestamp + 100, block.timestamp + 200);
        
        vm.prank(creator);
        voting.addContestant(1, "Con 1", "", "", "Party 1");
        vm.prank(creator);
        voting.addContestant(1, "Con 2", "", "", "Party 2");
        
        DigitalVoting.ContestantStruct[] memory contestantsArray = voting.getAllContestants(1);
        assertEq(contestantsArray.length, 2);
        assertEq(contestantsArray[0].id, 1);
        assertEq(contestantsArray[1].id, 2);
        assertEq(bytes(contestantsArray[0].name), bytes("Con 1"));
        assertEq(bytes(contestantsArray[1].name), bytes("Con 2"));
    }
}
