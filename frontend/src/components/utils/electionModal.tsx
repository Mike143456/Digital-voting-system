"use client"

import React from "react";

export default function Modal({ open, onClose, children, title }: { open: boolean; onClose: () => void; children: React.ReactNode; title?: string }) {
  if (!open) return null;
  return (
    <div className="fixed inset-0 z-50 flex items-end md:items-center justify-center p-4 md:p-6">
      <div className="absolute inset-0 bg-black/40" onClick={onClose} />
      <div className="relative w-full max-w-3xl bg-white rounded-2xl shadow-xl overflow-auto p-6">
        {title && <h3 className="text-lg font-semibold text-green-900 mb-3">{title}</h3>}
        <button onClick={onClose} className="absolute top-3 right-3 text-green-700">✕</button>
        <div>{children}</div>
      </div>
    </div>
  );
}
