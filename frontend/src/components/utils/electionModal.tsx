"use client";

import React from "react";

export default function Modal({
  open,
  onClose,
  children,
  title,
}: {
  open: boolean;
  onClose: () => void;
  children: React.ReactNode;
  title?: string;
}) {
  if (!open) return null;
  return (
    <div className="fixed inset-0 z-50 flex items-end md:items-center justify-center p-4 md:p-6">
      {/* Backdrop */}
      <div
        className="absolute inset-0 bg-black/70 backdrop-blur-sm"
        onClick={onClose}
      />

      {/* Modal content */}
      <div className="relative w-full max-w-3xl bg-black/80 border border-amber-500/20 rounded-2xl shadow-2xl p-6 text-white">
        {title && (
          <h3 className="text-lg font-semibold text-amber-400 mb-4">
            {title}
          </h3>
        )}

        {/* Close button */}
        <button
          onClick={onClose}
          className="absolute top-3 right-3 text-gray-300 hover:text-amber-400 transition"
        >
          ✕
        </button>

        <div>{children}</div>
      </div>
    </div>
  );
}
