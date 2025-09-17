"use client";

import { FC } from "react";
import { motion, AnimatePresence } from "framer-motion";
import { CheckCircle, XCircle, AlertTriangle } from "lucide-react";

type AlertType = "success" | "error" | "warning";

interface AlertModalProps {
  isOpen: boolean;
  title: string;
  subtitle: string;
  type?: AlertType;
  onClose: () => void;
}

const iconMap: Record<AlertType, React.ReactNode> = {
  success: <CheckCircle className="w-14 h-14 sm:w-16 sm:h-16 text-green-500" />,
  error: <XCircle className="w-14 h-14 sm:w-16 sm:h-16 text-red-500" />,
  warning: <AlertTriangle className="w-14 h-14 sm:w-16 sm:h-16 text-yellow-500" />,
};

const AlertModal: FC<AlertModalProps> = ({
  isOpen,
  title,
  subtitle,
  type = "success",
  onClose,
}) => {
  return (
    <AnimatePresence>
      {isOpen && (
        <motion.div
          className="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm px-4"
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          exit={{ opacity: 0 }}
        >
          <motion.div
            className="bg-white rounded-2xl shadow-xl w-full max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg p-6 text-center"
            initial={{ scale: 0.9, opacity: 0 }}
            animate={{ scale: 1, opacity: 1 }}
            exit={{ scale: 0.9, opacity: 0 }}
          >
            <div className="flex justify-center mb-4">{iconMap[type]}</div>

            <h2 className="text-lg sm:text-xl md:text-2xl font-semibold text-green-800 mb-2">
              {title}
            </h2>

            <p className="text-sm sm:text-base text-green-600 mb-6 leading-relaxed">
              {subtitle}
            </p>

            <button
              onClick={onClose}
              className="w-full bg-green-600 hover:bg-green-600 text-white py-2.5 sm:py-3 rounded-xl font-medium shadow-md transition"
            >
              Ok
            </button>
          </motion.div>
        </motion.div>
      )}
    </AnimatePresence>
  );
};

export default AlertModal;
