'use client'; // This page must be a client component as it uses state and effects

import { useEffect, useState } from 'react';
import Link from 'next/link';

// NOTE FOR USER: You may need to adjust this path based on your project structure.
// This is your existing import for the large dashboard component.
import VotingDashboard from '@/components/dashboard/voterDashboard'; 

// We need the blockchain utility functions for the M2 test
import { sendTransactionToRelayer } from '../../utils/blockchain';

// --- Dedicated Component for Milestone 2 Test ---
// This component performs the local transaction signing and sends it to the relayer API.
const M2TestRunner = () => {
    const [m2Status, setM2Status] = useState('IDLE');
    const [m2Message, setM2Message] = useState('Click button to run M2 test...');
    
    // Hardcoded test data for Milestone 2 validation
    const M2_TEST_PRIVATE_KEY = "0xac0974bec39a17e36ba4a6b4d238fec291b0daaab3551ba21cc412979212b7a2";
    const POLL_ID = 2;
    const CONTESTANT_ID = 1;

    // Function to perform the M2 logic
    const runM2Test = async () => {
        if (m2Status === 'RUNNING') return;

        setM2Status('RUNNING');
        setM2Message('Executing transaction signing and relayer transmission...');

        try {
            // M2 Test: Vote for the first contestant (ID 1)
            // This tests: 1) local signing, 2) serializing the request, 3) sending to your /api/relayer.
            await sendTransactionToRelayer(M2_TEST_PRIVATE_KEY, 'vote', [POLL_ID, CONTESTANT_ID]);
            
            setM2Status('SUCCESS');
            setM2Message('✅ M2 SUCCESS: Signed transaction sent to dummy relayer API.');

            // Log confirmation to the server console
            console.log('--- MILESTONE 2 FRONTEND CONFIRMATION ---');
            console.log('✅ M2 FRONTEND CONFIRMATION: The transaction was signed locally and successfully sent to the /api/relayer endpoint.');
            console.log('------------------------------------------');

        } catch (error) {
            setM2Status('FAILED');
            setM2Message(`❌ M2 FAILED: Signing or Relayer transmission failed. Error: ${error instanceof Error ? error.message : String(error)}`);
            console.error('❌ MILESTONE 2 ERROR:', error);
        }
    };


    // Render the M2 status visually
    return (
        <div className="max-w-7xl mx-auto mt-6 p-6 bg-gray-800/80 rounded-xl shadow-2xl border border-amber-500/30 backdrop-blur-sm">
            <h2 className="text-2xl font-bold text-amber-400 mb-4 border-b border-gray-700 pb-2">
                Milestone 2 Validation Test
            </h2>
             <div className={`p-4 rounded-lg flex justify-between items-center transition-colors duration-300 ${
                m2Status.includes('SUCCESS') ? 'bg-green-700 border-green-500' : 
                m2Status.includes('FAILED') ? 'bg-red-700 border-red-500' : 
                'bg-blue-700 border-blue-500'
             } border-l-4`}>
                <span className="font-medium text-white">M2 Status:</span>
                <span className="font-mono text-sm">{m2Message}</span>
            </div>
            
            <button
                onClick={runM2Test}
                disabled={m2Status === 'RUNNING' || m2Status === 'SUCCESS'}
                className="w-full mt-6 px-6 py-3 text-lg font-semibold text-black rounded-lg shadow-xl transition duration-300 transform 
                           focus:outline-none focus:ring-4 focus:ring-amber-500/50
                           disabled:bg-gray-500 disabled:cursor-not-allowed
                           bg-amber-500 hover:bg-amber-400"
            >
                {m2Status === 'RUNNING' ? 'Signing & Sending...' : m2Status === 'SUCCESS' ? 'M2 Test Passed!' : 'Run Milestone 2 Test (Sign & Send Vote)'}
            </button>

            <p className="mt-4 text-center text-gray-400 text-xs">
                (This test ensures your client-side transaction signing and relayer communication logic is correct. Check your **server console** for the final **BACKEND CONFIRMATION**.)
            </p>
        </div>
    );
};


// Main Dashboard Page Component
export default function DashboardPage() {
    return (
        <div className="relative w-screen min-h-screen bg-black overflow-hidden">
            <div className="relative z-10 p-4 md:p-8 text-white">
                <header className="max-w-7xl mx-auto flex items-center justify-between gap-4">
                    <h1 className="text-3xl font-bold text-indigo-400">
                        User Dashboard
                    </h1>
                    <Link href="/" className="text-sm text-gray-400 hover:text-white transition duration-200">
                        ← Back to Landing (M1 Status)
                    </Link>
                </header>

                {/* Render the dedicated M2 test runner first */}
                <M2TestRunner />
                
                {/* Render the user's main dashboard UI below */}
                <div className="mt-8">
                    {/* NOTE: Assuming the import above is correct for your structure */}
                    <VotingDashboard />
                </div>
            </div>
        </div>
    );
}
