import { createPublicClient, http, parseAbi, encodeFunctionData, createWalletClient } from 'viem';
import { privateKeyToAccount } from 'viem/accounts';
import ABI from './abi.json'

export { publicClient, fetchAPollData, fetchAContestant, fetchAllPollData, sendTransactionToRelayer }; 

// RPC and Contract Configuration
const LISK_RPC_URL = process.env.NEXT_PUBLIC_LISK_RPC_URL || 'https://rpc.sepolia-api.lisk.com';
const CONTRACT_ADDRESS = process.env.NEXT_PUBLIC_CONTRACT_ADDRESS || '0x94dd8c3f2Df10b43F7e3f1517A530F43cB8d609c';
const RELAYER_API_ENDPOINT = process.env.NEXT_PUBLIC_RELAYER_API || '/api/relayer'; 

// --- VIEM CLIENT SETUP ---

const liskSepolia = {
    id: 4202,
    name: 'Lisk Sepolia Testnet',
    rpcUrls: {
        default: {
            http: [LISK_RPC_URL]
        }
    }
};

const publicClient = createPublicClient({
    chain: liskSepolia,
    transport: http(),
})

// Test RPC connectivity
console.log('Testing RPC connection:', await publicClient.getBlockNumber().catch(err => `Failed: ${err.message}`));

// The contract ABI - Fixed to match actual contract structure
// const contractAbi = parseAbi([
//     'function createPoll(string _title, string _description, uint _duration, uint _start, string[] _contestantNames, string[] _contestantImages) returns (uint)',
//     'function vote(uint256 _pollId, uint256 _contestantId) external',
//     'function getAPoll(uint256) view returns (tuple(uint id, string title, string description, string imageUrl, uint startTime, uint endTime, bool isActive, uint totalVotes, uint contestantCount, address creator, uint createdAt, address[] voters))',
//     'function getAContestant(uint256, uint256) view returns (uint, string, string, string, string, uint)',
//     'function getAllPolls() view returns (tuple(uint id, string title, string description, string imageUrl, uint startTime, uint endTime, bool isActive, uint totalVotes, uint contestantCount, address creator, uint createdAt, address[] voters)[])'
// ])

const contractAbi = ABI
console.log(contractAbi);


// --- READ FUNCTIONS (MILESTONE 1) ---

async function fetchAPollData(pollId) {
    try {
        // Log raw data to inspect the contract's response
    // const rawData = await publicClient.call({
    //   to: CONTRACT_ADDRESS,
    //   data: encodeFunctionData({
    //     abi: contractAbi,
    //     functionName: 'getAPoll',
    //     args: [pollId],
    //   }),
    // });
    // console.log('Raw data for pollId', pollId, ':', rawData);
    const response = await publicClient.readContract({
      address: CONTRACT_ADDRESS,
      abi: contractAbi,
      functionName: 'getAPoll',
      args: [pollId],
    });
    console.log(`Data for poll Id ${pollId}:`, response);
    return response;
  } catch (error) {
    console.error(`Error in fetching poll data of id ${pollId}:`, error);
    return null;
    //     const response = await publicClient.readContract({
    //         address: CONTRACT_ADDRESS,
    //         abi: contractAbi,
    //         functionName: 'getAPoll',
    //         args: [pollId]
    //     })
    //     console.log(`Data for poll Id ${pollId}:`, response);
    //     return response;
        
    // } catch (error) {
    //     console.error(`Error in fetching poll data of id ${pollId}`, error);
    //     return null;
    }
}

async function fetchAllPollData() {
    try {
        const allPollRes = await publicClient.readContract({
            address: CONTRACT_ADDRESS,
            abi: contractAbi,
            functionName: 'getAllPolls',
        })
        console.log("All poll available:", allPollRes);
        return allPollRes;
        
    } catch (error) {
        console.error("Error in fetching all polls:", error);
        return null
    }
}

async function fetchAContestant(pollId, contestantId) {
    try {
        const contResponse = await publicClient.readContract({
            address: CONTRACT_ADDRESS,
            abi: contractAbi,
            functionName: 'getAContestant',
            args: [pollId, contestantId]
        })
        console.log(`Data for contestant Id ${contestantId}, in poll id ${pollId}:`, contResponse);
        return contResponse;
        
    } catch (error) {
        console.error(`Error in fetching contestant data of id ${contestantId}`, error);
        return null;
    }
}


/**
 * Signs a transaction locally using the user's private key and returns the raw signed transaction hex.
 * FIXED: Now properly derives account from private key
 */
async function signTransaction(privateKey, functionName, args) {
    try {
        // CRITICAL FIX: Derive the account from the private key
        const account = privateKeyToAccount(privateKey);
        
        console.log(`[SIGN] Signing transaction for account: ${account.address}`);
        
        // Create a wallet client for this specific account
        const walletClient = createWalletClient({
            account,
            chain: liskSepolia,
            transport: http(LISK_RPC_URL),
        });
        
        // 1. Encode the function data
        const data = encodeFunctionData({
            abi: contractAbi,
            functionName,
            args,
        });
        
        // 2. Get the latest nonce
        const nonce = await publicClient.getTransactionCount({ 
            address: account.address 
        });
        
        // 3. Estimate gas
        const gasEstimate = await publicClient.estimateContractGas({
            account: account.address,
            address: CONTRACT_ADDRESS,
            abi: contractAbi,
            functionName,
            args,
        });
        
        // 4. Get current gas price
        const gasPrice = await publicClient.getGasPrice();
        
        // 5. Prepare the transaction
        const transactionRequest = {
            account,
            to: CONTRACT_ADDRESS,
            data,
            gas: gasEstimate,
            gasPrice,
            nonce,
            chain: liskSepolia,
        };
        
        console.log('[SIGN] Transaction prepared:', {
            to: transactionRequest.to,
            nonce: transactionRequest.nonce,
            gas: transactionRequest.gas?.toString(),
        });
        
        // 6. Sign the transaction
        const signedTx = await walletClient.signTransaction(transactionRequest);
        
        console.log('[SIGN] Transaction signed successfully. Length:', signedTx.length);
        
        return signedTx;

    } catch (error) {
        console.error("Error during transaction signing:", error);
        throw new Error(`Failed to sign transaction: ${error.message}`);
    }
}


/**
 * MILESTONE 2: Signs the transaction locally and sends the signed payload to the relayer.
 */
async function sendTransactionToRelayer(privateKey, functionName, args) {
    console.log(`[M2] Starting sign and send for function: ${functionName}`);

    try {
        // 1. SIGN THE TRANSACTION LOCALLY
        const signedTx = await signTransaction(privateKey, functionName, args);

        // 2. SEND THE SIGNED PAYLOAD TO THE RELAYER API
        console.log(`[M2] Signed transaction. Sending to Relayer at ${RELAYER_API_ENDPOINT}`);
        
        const response = await fetch(RELAYER_API_ENDPOINT, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ signedTx }),
        });
        
        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`Relayer failed: ${response.status} - ${errorText}`);
        }
        
        const result = await response.json();
        console.log("[M2] Relayer API Response:", result);
        return result.message;
        
    } catch (error) {
        console.error('[M2] Error:', error);
        throw error;
    }
}