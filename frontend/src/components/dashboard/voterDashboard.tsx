"use client"

import React, { useMemo, useState } from "react";
import { CandidateType, ResultRowType, ElectionType } from './types/dashboardTypes';
import Modal from "../utils/electionModal";

type StateResults = Record<string, ResultRowType[]>; // e.g. { Governor: [...], "House of Assembly": [...] }

type ElectionResults = Record<string, StateResults>; // e.g. { Lagos: {...}, Kano: {...} }

const dummyElection: ElectionType[] = [
  {
    id: "pres2025",
    title: "Presidential Election",
    type: "federal",
    level: "federal",
    active: true,
    candidates: [
      {
        id: "p1",
        name: "Adebayo Olu",
        party: "Green Progress",
        town: "Ikeja",
        state: "Lagos",
        image: "https://randomuser.me/api/portraits/men/2.jpg",
        deputy: { name: "Chinwe Okafor", image: "https://via.placeholder.com/120?text=Chinwe" },
      },
      {
        id: "p2",
        name: "Fatima Sani",
        party: "Unity Party",
        town: "Kano",
        state: "Kano",
        image: "https://randomuser.me/api/portraits/men/2.jpg",
        deputy: { name: "Ibrahim Musa", image: "https://via.placeholder.com/120?text=Ibrahim" },
      },
    ],
  },
  {
    id: "senate2025",
    title: "Senate Elections",
    type: "federal",
    level: "federal",
    active: true,
    seats: "Senate",
    candidates: [
      {
        id: "s1",
        name: "D. Ajayi",
        party: "Green Progress",
        town: "Abeokuta",
        state: "Ogun",
        image: "https://randomuser.me/api/portraits/men/2.jpg",
        deputy: null 
      },
      { 
        id: "s2",
        name: "R. Balogun",
        party: "People's Front",
        town: "Ilaro",
        state: "Ogun",
        image: "https://randomuser.me/api/portraits/men/2.jpg",
        deputy: null 
      },
    ],
  },
  {
    id: "gov2025",
    title: "Governorship - Lagos",
    type: "state",
    level: "state",
    active: true,
    seats: "Governor",
    candidates: [
      {
        id: "g1",
        name: "Bayo Adeleke",
        party: "Green Progress",
        town: "Ikeja",
        state: "Lagos",
        image: "https://randomuser.me/api/portraits/men/2.jpg",
        deputy: { name: "Segun A.", image: "https://randomuser.me/api/portraits/men/2.jpg" } 
      },
      { 
        id: "g2", 
        name: "Nkechi Eze",
        party: "Progressive Union",
        town: "Epe",
        state: "Lagos",
        image: "https://randomuser.me/api/portraits/men/2.jpg",
        deputy: { name: "Tunde K.", image: "https://randomuser.me/api/portraits/men/2.jpg" } 
      },
    ],
  },
  {
    id: "houseAssembly",
    title: "House of Assembly - Lagos",
    type: "state",
    level: "state",
    active: false, // not open today, should be filtered out
    seats: "House of Assembly",
    candidates: [],
  },
];

const Badge = ({ children }: { children: React.ReactNode }) => {
  return (
    <span className="inline-flex items-center gap-2 px-2 py-1 rounded-full text-xs font-medium bg-white/20 backdrop-blur-sm text-white">
      {children}
    </span>
  );
}

const IconLive =()=> {
  return (
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden>
      <path d="M12 5v14" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
      <path d="M5 9v6" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
      <path d="M19 9v6" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
    </svg>
  );
}

export default function VotingDashboard() {
  const [elections] = useState<ElectionType[]>(dummyElection);
  const activeElections = useMemo(() => elections.filter((e) => e.active), [elections]);

  const [selectedElection, setSelectedElection] = useState<ElectionType | null>(null);
  const [selectedCandidate, setSelectedCandidate] = useState<CandidateType | null>(null);
  const [showLive, setShowLive] = useState(false);
  const [liveScope, setLiveScope] = useState<"federal" | "state" | "presidential">("federal");
  const [selectedState, setSelectedState] = useState<string>("Lagos");

  const dummyResults: ElectionResults = useMemo(() => {
    return {
      Lagos: {
        Governor: [
          { name: "Bayo Adeleke", votes: 321_456, party: "Green Progress" },
          { name: "Nkechi Eze", votes: 201_234, party: "Progressive Union" },
        ],
        "House of Assembly": [
          { name: "A. Representative", votes: 120_000, party: "Green Progress" },
          { name: "B. Representative", votes: 100_500, party: "People's Front" },
        ],
      },
      Kano: {
        Senate: [
          { name: "Fatima Sani", votes: 210_000, party: "Unity Party" },
        ],
      },
    };
  }, []);

  function openElection(e: ElectionType) {
    setSelectedElection(e);
  }

  function openCandidate(candidate: CandidateType, parentElection: ElectionType) {
    setSelectedCandidate(candidate);
    setSelectedElection(parentElection);
  }

  return (
    <div className="min-h-screen bg-gradient-to-b from-green-50 via-white to-green-50 p-4 md:p-8 text-green-900">
      <header className="max-w-7xl mx-auto flex items-center justify-between gap-4">
        <div className="flex items-center gap-3">
          <div className="w-12 h-12 rounded-full bg-white/30 flex items-center justify-center ring-2 ring-white">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" className="text-white">
              <path d="M12 2L15 8H9L12 2Z" fill="currentColor" />
            </svg>
          </div>
          <div>
            <h1 className="text-xl md:text-2xl font-bold text-green-900">National Voting Dashboard</h1>
            <p className="text-sm text-green-800/70">Showing elections open today — select a card to vote or view details</p>
          </div>
        </div>

        <div className="flex items-center gap-3">
          <button onClick={() => setShowLive(true)} className="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-green-800 text-white shadow-md hover:bg-green-700">
            <IconLive />
            <span className="hidden sm:inline">Live Results</span>
          </button>
          <div className="hidden md:block text-sm text-green-900/80">User: <strong>Glory</strong></div>
        </div>
      </header>

      <main className="max-w-7xl mx-auto mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {activeElections.length === 0 ? (
          <div className="col-span-full bg-white/60 p-6 rounded-2xl text-center">
            <h3 className="font-semibold text-lg">No elections open today</h3>
            <p className="text-sm text-green-800/70 mt-2">Come back on the scheduled election day to see voting cards.</p>
          </div>
        ) : (
          activeElections.map((e) => (
            <article key={e.id} className="bg-white/80 p-4 rounded-2xl shadow-sm ring-1 ring-green-100">
              <div className="flex items-start justify-between gap-3">
                <div>
                  <h3 className="text-lg font-bold text-green-900">{e.title}</h3>
                  <p className="text-sm text-green-800/80">{e.seats ?? e.type.toUpperCase()}</p>
                  <div className="mt-3 flex flex-wrap gap-2">
                    <Badge>{e.level.toUpperCase()}</Badge>
                    {e.seats && <Badge>{e.seats}</Badge>}
                  </div>
                </div>
                <div className="flex flex-col items-end gap-2">
                  <button onClick={() => openElection(e)} className="px-3 py-1 rounded-lg bg-green-50 text-green-900 text-sm">Open</button>
                  <button onClick={() => setShowLive(true)} className="px-3 py-1 rounded-lg bg-green-800 text-white text-sm">Live</button>
                </div>
              </div>

              <div className="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                {e.candidates.slice(0, 4).map((c) => (
                  <button key={c.id} onClick={() => openCandidate(c, e)} className="group text-left bg-green-50/40 p-2 rounded-lg flex items-center gap-3 hover:scale-[1.01] transition-transform">
                    <img src={c.image} alt={c.name} className="w-14 h-14 rounded-md object-cover shadow-sm" />
                    <div>
                      <div className="text-sm font-semibold text-green-900">{c.name}</div>
                      <div className="text-xs text-green-800/80">{c.party} • {c.state}</div>
                    </div>
                  </button>
                ))}
              </div>

              <div className="mt-4 flex items-center justify-between text-sm text-green-800/80">
                <div>{e.candidates.length} candidate(s)</div>
                <div className="flex items-center gap-2">
                  <button className="text-xs px-2 py-1 rounded bg-white/30">Details</button>
                  <button className="text-xs px-2 py-1 rounded bg-green-900 text-white">Vote</button>
                </div>
              </div>
            </article>
          ))
        )}
      </main>

      {/* Candidate / Election Detail Modal */}
      <Modal open={!!selectedCandidate} onClose={() => setSelectedCandidate(null)} title={selectedCandidate ? selectedCandidate.name : undefined}>
        {selectedCandidate && (
          <div className="flex flex-col md:flex-row gap-4">
            <div className="w-full md:w-1/3 flex flex-col gap-3 items-center">
              <img src={selectedCandidate.image} alt={selectedCandidate.name} className="w-44 h-44 rounded-lg object-cover" />
              <div className="text-sm text-green-900 font-semibold">{selectedCandidate.party}</div>
              <div className="text-xs text-green-800/70">{selectedCandidate.town}, {selectedCandidate.state}</div>
            </div>
            <div className="flex-1">
              <h4 className="font-semibold text-green-900">About</h4>
              <p className="text-sm text-green-800/80 mt-2">This is sample candidate information. In production, we&apos;ll fetch the candidate&apos;s full profile including manifesto, verified ID and polling unit allocation.</p>

              {selectedCandidate.deputy && (
                <div className="mt-4">
                  <h5 className="font-semibold">Running mate / Deputy</h5>
                  <div className="mt-2 flex items-center gap-3">
                    <img src={selectedCandidate.deputy.image} alt={selectedCandidate.deputy.name} className="w-16 h-16 rounded-md object-cover" />
                    <div>
                      <div className="text-sm font-medium">{selectedCandidate.deputy.name}</div>
                    </div>
                  </div>
                </div>
              )}

              <div className="mt-6 flex gap-3">
                <button className="px-4 py-2 rounded-lg bg-green-900 text-white">Cast Vote</button>
                <button onClick={() => setSelectedCandidate(null)} className="px-4 py-2 rounded-lg bg-white text-green-900 ring-1 ring-green-100">Close</button>
              </div>
            </div>
          </div>
        )}
      </Modal>

      {/* Live Results Modal */}
      <Modal open={showLive} onClose={() => setShowLive(false)} title={"Live Results"}>
        <div className="space-y-4">
          <div>
            <label className="block text-sm font-medium text-green-900">Choose result scope</label>
            <div className="mt-2 flex gap-2">
              <button onClick={() => setLiveScope("presidential")} className={`px-3 py-2 rounded-lg ${liveScope === "presidential" ? "bg-green-900 text-white" : "bg-white text-green-900"}`}>
                Presidential
              </button>
              <button onClick={() => setLiveScope("federal")} className={`px-3 py-2 rounded-lg ${liveScope === "federal" ? "bg-green-900 text-white" : "bg-white text-green-900"}`}>
                Federal (Senate / Reps)
              </button>
              <button onClick={() => setLiveScope("state")} className={`px-3 py-2 rounded-lg ${liveScope === "state" ? "bg-green-900 text-white" : "bg-white text-green-900"}`}>
                State (Governor / Assembly)
              </button>
            </div>
          </div>

          {/* State selection for state-scope results */}
          {liveScope !== "presidential" && (
            <div>
              <label className="block text-sm font-medium text-green-900">Select State</label>
              <select className="mt-2 block w-full rounded-lg px-3 py-2" value={selectedState} onChange={(e) => setSelectedState(e.target.value)}>
                {Object.keys(dummyResults).map((s) => (
                  <option key={s} value={s}>{s}</option>
                ))}
              </select>
            </div>
          )}

          {/* Render a simple live result summary */}
          <div className="mt-2">
            <h4 className="text-green-900 font-semibold">Live: {liveScope === "presidential" ? "Presidential" : `${liveScope.charAt(0).toUpperCase() + liveScope.slice(1)} — ${selectedState}`}</h4>

            <div className="mt-3 space-y-3">
              {(() => {
                const stateData = dummyResults[selectedState];
                if (!stateData) return <div className="text-sm text-green-800/70">No live feed available for selected state.</div>;

                // pick first seat key for demo
                const seatKey = Object.keys(stateData)[0];
                const rows = stateData[seatKey];
                return (
                  <div>
                    <div className="text-sm text-green-800/80">Showing: <strong>{seatKey}</strong></div>
                    <div className="mt-2 grid gap-3">
                      {rows.map((r: ResultRowType) => {
                        const totalVotes = rows.reduce((s, v) => s + v.votes, 0) || 1;
                        const pct = Math.round((r.votes / totalVotes) * 100);

                        return (
                          <div key={r.name} className="bg-white/80 p-3 rounded-lg">
                            <div className="flex items-center justify-between">
                              <div>
                                <div className="font-semibold text-green-900">{r.name}</div>
                                <div className="text-xs text-green-800/70">{r.party}</div>
                              </div>
                              <div className="text-right">
                                <div className="font-medium text-green-900">{r.votes.toLocaleString()}</div>
                                <div className="text-xs text-green-800/70">{pct}%</div>
                              </div>
                            </div>

                            <div className="mt-2 h-2 bg-green-50 rounded-full overflow-hidden">
                              <div
                                style={{ width: `${pct}%` }}
                                className="h-full rounded-full bg-green-900/80"
                              />
                            </div>
                          </div>
                        );
                      })}
                    </div>
                  </div>
                );
              })()}
            </div>
          </div>

          <div className="flex justify-end">
            <button onClick={() => setShowLive(false)} className="px-4 py-2 rounded-lg bg-white text-green-900 ring-1 ring-green-100">Close</button>
          </div>
        </div>
      </Modal>

      <footer className="max-w-7xl mx-auto mt-10 text-center text-sm text-green-800/60">Built for Nigeria</footer>
    </div>
  );
}
