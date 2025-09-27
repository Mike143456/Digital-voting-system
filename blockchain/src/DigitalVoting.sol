// SPDX-License-Identifier: UNLICENSED
pragma solidity ^0.8.13;

contract DigitalVoting {
    // Define a struct for a Poll
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

    // Defines a struct for a Contestant
    struct ContestantStruct {
        uint id;
        string name;
        string desc;
        string image;
        string partyName;
        uint votes;
    }

    // State variables
    PollStruct[] public polls;
    mapping(uint => ContestantStruct[]) public pollToContestant;
    mapping(uint => mapping(address => bool)) private voterHasVoted;

    // Events
    event PollCreated(
        uint indexed id,
        string title,
        address indexed creator
    );
    event ContestantAdded(
        uint indexed pollId,
        uint indexed contestantId,
        string name
    );
    event Voted(
        uint indexed pollId,
        uint indexed contestantId,
        address voter
    );
    event PollUpdated(uint indexed pollId);

    // Modifiers
    modifier onlyCreator(uint _pollId) {
        require(
            polls[_pollId - 1].creator == msg.sender,
            "Only the poll creator can perform this action"
        );
        _;
    }

    // Functions to create and manage polls
    function createPoll(
        string memory _title,
        string memory _image,
        string memory _desc,
        uint _startsAt,
        uint _endsAt
    ) public {
        require(bytes(_title).length > 0, "Title is required");
        require(
            _endsAt > _startsAt && _endsAt > block.timestamp,
            "Invalid end time"
        );

        uint pollId = polls.length + 1;
        polls.push(
            PollStruct(
                pollId,
                _title,
                _desc,
                _image,
                0,
                0,
                false,
                _startsAt,
                _endsAt,
                msg.sender,
                block.timestamp,
                new address[](0)
            )
        );
        emit PollCreated(pollId, _title, msg.sender);
    }

    function updatePoll(
        uint _pollId,
        string memory _title,
        string memory _image,
        string memory _desc
    ) public onlyCreator(_pollId) {
        require(
            polls[_pollId - 1].startsAt > block.timestamp,
            "Poll has already started and cannot be updated"
        );
        polls[_pollId - 1].title = _title;
        polls[_pollId - 1].image = _image;
        polls[_pollId - 1].desc = _desc;
        emit PollUpdated(_pollId);
    }

    function deletePoll(uint _pollId) public onlyCreator(_pollId) {
        polls[_pollId - 1].deleted = true;
    }

    // Functions to manage contestants
    function addContestant(
        uint _pollId,
        string memory _name,
        string memory _desc,
        string memory _image,
        string memory _partyName
    ) public onlyCreator(_pollId) {
        require(
            polls[_pollId - 1].startsAt > block.timestamp,
            "Poll has already started"
        );
        require(
            !contestantExists(_pollId, _name, _partyName),
            "Contestant already exists for this poll"
        );

        uint contestantId = pollToContestant[_pollId].length + 1;
        pollToContestant[_pollId].push(
            ContestantStruct(
                contestantId,
                _name,
                _desc,
                _image,
                _partyName,
                0
            )
        );
        polls[_pollId - 1].contestants++;
        emit ContestantAdded(_pollId, contestantId, _name);
    }

    function contestantExists(
        uint _pollId,
        string memory _name,
        string memory _partyName
    ) internal view returns (bool) {
        for (uint i = 0; i < pollToContestant[_pollId].length; i++) {
            if (
                keccak256(
                    abi.encodePacked(pollToContestant[_pollId][i].name)
                ) == keccak256(abi.encodePacked(_name)) &&
                keccak256(
                    abi.encodePacked(pollToContestant[_pollId][i].partyName)
                ) == keccak256(abi.encodePacked(_partyName))
            ) {
                return true;
            }
        }
        return false;
    }

    // Function to handle voting
    function vote(uint _pollId, uint _contestantId) public {
        require(!voterHasVoted[_pollId][msg.sender], "Already voted!!!");
        require(
            block.timestamp > polls[_pollId - 1].startsAt,
            "Voting has not started"
        );
        require(
            block.timestamp < polls[_pollId - 1].endsAt,
            "Voting has ended"
        );
        polls[_pollId - 1].voteCount++;
        pollToContestant[_pollId][_contestantId - 1].votes++;
        polls[_pollId - 1].voters.push(msg.sender);
        voterHasVoted[_pollId][msg.sender] = true;
        emit Voted(_pollId, _contestantId, msg.sender);
    }

    // View functions
    function getAPoll(uint _pollId)
        public
        view
        returns (PollStruct memory)
    {
        return polls[_pollId - 1];
    }

    function getAContestant(uint _pollId, uint _contestantId)
        public
        view
        returns (ContestantStruct memory)
    {
        return pollToContestant[_pollId][_contestantId - 1];
    }

    function getAllPolls() public view returns (PollStruct[] memory) {
        return polls;
    }

    function getAllContestants(uint _pollId)
        public
        view
        returns (ContestantStruct[] memory)
    {
        return pollToContestant[_pollId];
    }
}
