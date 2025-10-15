/**
 * Relayer API Route for Next.js 13+ App Router
 * File location: src/app/api/relayer/route.js
 * 
 * MILESTONE 2: Receives signed transactions from frontend
 * MILESTONE 3: Will batch and broadcast to blockchain
 */

import { NextResponse } from 'next/server';

// For Milestone 3, we'll need these:
// import { createPublicClient, http } from 'viem';

export async function POST(request) {
    try {
        const body = await request.json();
        const { signedTx } = body;

        if (!signedTx) {
            return NextResponse.json(
                { message: 'Missing signedTx payload' },
                { status: 400 }
            );
        }

        // Log the successful receipt
        console.log("--- MILESTONE 2 BACKEND CONFIRMATION ---");
        console.log("✅ DUMMY RELAYER RECEIVED SIGNED TRANSACTION PAYLOAD");
        console.log(`Payload length: ${signedTx.length}`);
        console.log(`First 20 chars: ${signedTx.substring(0, 20)}...`);
        console.log("------------------------------------------");

        // TODO MILESTONE 3: Store in queue/database for batching
        // For now, just acknowledge receipt
        
        return NextResponse.json({ 
            message: "Transaction payload successfully received by relayer API. (M2 PASS)",
            txQueued: true,
            timestamp: new Date().toISOString()
        });

    } catch (error) {
        console.error("Relayer API Error:", error);
        return NextResponse.json(
            { message: `Error processing request: ${error.message}` },
            { status: 500 }
        );
    }
}

// Handle other HTTP methods
export async function GET(request) {
    return NextResponse.json({ 
        message: "Relayer API is running. Use POST to submit transactions." 
    });
}