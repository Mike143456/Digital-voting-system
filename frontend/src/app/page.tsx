import LoginForm from "@/components/auth/login";
import { fetchAPollData } from '@/utils/blockchain'; // <-- Importing the fetch function

// Make the function async to allow awaiting the blockchain call
export default async function HomePage() {
  
  // --- MILESTONE 1 QUICK TEST (Logs to VS Code Terminal) ---
  try {
    // This call executes on the server during the render process.
    console.log("--- MILESTONE 1 SERVER-SIDE QUICK TEST STARTING ---");
    
    // Call the data fetcher for Poll ID 1
    const testPollData = await fetchAPollData(1);
    
    if (testPollData) {
        // This is the success log you are looking for!
        console.log("✅ MILESTONE 1 SUCCESS: Poll Data from Lisk Sepolia:", testPollData);
        console.log("-------------------------------------------------");
    } else {
        console.log("❌ MILESTONE 1 FAILED: Check RPC connection and contract address.");
    }
  } catch (e) {
    // Catches errors during server component rendering (like network issues)
    console.error("Critical Server Fetch Error:", e);
  }
  // ------------------------------------------------------------------

  return (
    <main className="min-h-screen p-24 bg-gray-50 flex flex-col items-center">
        
        <div className="mt-8">
            <LoginForm />
        </div>
    </main>
  );
}
