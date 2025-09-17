export type CandidateType = {
  id: string;
  name: string;
  party: string;
  town: string;
  state: string;
  image?: string;
  deputy?: {
    name: string;
    image?: string;
  } | null;
};

export type ElectionType = {
  id: string;
  title: string; // e.g. "Presidential Election"
  type: "federal" | "state" | "local";
  active: boolean; // whether this election is open today
  level: "federal" | "state"; // for routing live results
  seats?: string; // e.g. "Senate", "House of Reps"
  candidates: CandidateType[];
};

export type ResultRowType = {
  name: string;
  votes: number;
  party: string;
};
