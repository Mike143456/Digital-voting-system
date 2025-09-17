"use client";
import { FieldError } from "react-hook-form";

interface InputFieldProps extends React.InputHTMLAttributes<HTMLInputElement> {
  label: string;
  error?: FieldError;
}

export default function CustomInput({ label, error, ...props }: InputFieldProps) {
  return (
    <div className="mb-4">
      <label className="block text-sm font-bold mb-1 text-green-400">{label}</label>
      <input
        {...props}
        className={`w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 text-neutral-800 ${
          error ? "border-red-500 focus:ring-red-400" : "border-gray-300 focus:ring-blue-400"
        }`}
      />
      {error && <p className="text-sm text-red-500 mt-1">{error.message}</p>}
    </div>
  );
}
