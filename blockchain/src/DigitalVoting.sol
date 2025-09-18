// SPDX-License-Identifier: MIT
pragma solidity ^0.8.13;

contract DigitalVoting {
    uint public counter;

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
    string image;
    uint votes;
   }

   mapping (uint => bool) pollExists;
   mapping (uint => PollStruct) polls;
   mapping (uint => mapping (address => bool)) hasVoted;
   mapping (uint => mapping (address => bool)) hasContested;
   mapping (uint => ContestantStruct) contestants;

   modifier onlyCreator(uint pollId) {
    require(msg.sender == polls[pollId].creator, "Unauthorised call!");
    _;
   }

   event HasVoted(address indexed voter, uint timeStamp);
   event PollCreated(string title, address indexed creator, uint indexed id, uint timestamp);
   event PollUpdated(address indexed admin, uint pollId);
   event PollDeleted(address indexed admin, uint pollId);

   function createPoll(
    string memory _title,
    string memory _image,
    string memory _desc,
    uint startTime,
    uint endTime
   ) public {
    counter++;

    require(bytes(_title).length > 3, "Please provide a valid poll title");
    require(startTime > 0, "Please insert a valid time for the commencement of the poll");
    require(endTime > startTime, "Please put a valid time to end the poll");

    PollStruct storage newPoll = polls[counter];
    newPoll.title = _title;
    newPoll.desc = _desc;
    newPoll.image = _image;
    newPoll.voteCount = 0;
    newPoll.contestants = 0;
    newPoll.deleted = false;
    newPoll.startsAt = startTime;
    newPoll.endsAt = endTime;
    newPoll.creator = msg.sender;
    newPoll.timeForCreatingPoll = block.timestamp;

    pollExists[counter] = true;   

    emit PollCreated(_title, msg.sender, counter, block.timestamp);                                                                                 
   }

   function updatePoll(
    uint pollId, 
    string memory _title, 
    string memory _image, 
    string memory _desc
) onlyCreator(pollId) public {
        require(bytes(_title).length > 3, "Please provide a valid title");
        require(!polls[pollId].deleted, "The poll you wish to update does not exist or has been deleted.");
        //cannot update if votes exist
        require(polls[pollId].voteCount == 0, "Poll has valid votes and cannot be updated");
        //cannot update if vote has ended
        require(block.timestamp < polls[pollId].endsAt, "This Poll already ended, cannot update.");

            polls[pollId].title = _title;
            polls[pollId].image = _image;
            polls[pollId].desc = _desc;

            emit PollUpdated(msg.sender, pollId);
    }

    function deletePoll(uint pollId) public {
        require(!polls[pollId].deleted, "Poll does not exist or already deleted.");
        //cannot delete if votes exist
        require(polls[pollId].voteCount == 0, "Poll has valid votes, cannot be deleted");
        //cannot delete if vote has ended
        require(block.timestamp < polls[pollId].endsAt, "Poll already ended, cannot be deleted");

        polls[pollId].deleted = true ;

        emit PollDeleted(msg.sender, pollId);
    }
}
