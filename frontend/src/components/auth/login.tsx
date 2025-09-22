"use client";
import { useForm } from "react-hook-form";
import { useState, useEffect } from "react";
import CustomInput from "../utils/customInput";
import { LoginData } from "./types/formTypes";
import { useRouter } from "next/navigation";
import AlertModal from "../utils/alertModal";
import Loader from "../utils/loader";
import { motion, useAnimation } from "framer-motion";

export default function LoginForm() {
  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm<LoginData>();

  const router = useRouter();
  const [successModal, setSuccessModal] = useState(false);
  const [signupError, setSignupError] = useState("");
  const [errorModal, setErrorModal] = useState(false);

  const loading = false;
  const error = false;

  const controls = useAnimation();

  useEffect(() => {
    const animateStars = async () => {
      while (true) {
        await controls.start({ scale: 1.30, transition: { duration: 3, ease: "easeInOut" } });
        await controls.start({ scale: 1, transition: { duration: 3, ease: "easeInOut" } });
      }
    };
    animateStars();
  }, [controls]);

  const onSubmit = async (data: LoginData) => {
    if (error) {
      setSignupError("Erorr signing up");
      setErrorModal(true);
    };
    console.log(data);
    setSuccessModal(true);
    setTimeout(() => {
      router.push("/dashboard");
    }, 3000);
  };

  return (
    <div className="relative w-screen h-screen bg-black flex items-center justify-center overflow-hidden">
      
      <motion.div
        animate={controls}
        className="absolute inset-0 bg-[radial-gradient(circle,_white_1px,_transparent_1px)] [background-size:18px_18px] opacity-15"
      />

    
      <div className="absolute top-0 left-0 w-1/2 h-1/2 bg-gradient-to-br from-yellow-300 via-orange-400 to-transparent opacity-60 blur-3xl" />

     
      <motion.div
        initial={{ opacity: 1, y: 30 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 1 }}
        className="relative z-10 backdrop-blur-md bg-white/5 border border-white/10 p-10 rounded-2xl shadow-2xl w-[90%] max-w-md"
      >
        <div className="flex flex-col items-center mb-6">
          <div className="w-12 h-12 bg-white/10 rounded-full flex items-center justify-center mb-3">
            <span className="text-white text-2xl"><img src="/images/download-removebg-preview.png" alt="logo" /></span>
          </div>
          <h2 className="text-white text-xl font-bold">Welcome To The Digital Poll System</h2>
          <p className="text-gray-300 text-sm">Please Enter Your Details To Continue</p>
        </div>

        <form className="flex flex-col gap-4">
          <input
            type="text"
            placeholder="Enter Your Voter's ID(Voter's ID)"
            className="p-3 rounded-lg bg-white/10 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-400"
          />
          <input
            type="text"
            placeholder="Enter The Phone Number"
            className="p-3 rounded-lg bg-white/10 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-400"
          />

          <div className="flex justify-between items-center text-gray-400 text-xs">
            <label className="flex items-center gap-1">
              <input type="checkbox" className="accent-orange-400" /> Remember this browser
            </label>
            <a href="#" className="hover:underline">No Voter&apos;s ID?</a>
          </div>

          <button
            type="submit"
            className="bg-white/90 hover:bg-white text-black font-bold py-2 rounded-lg shadow-lg transition duration-300"
          >
            Login
          </button>

          <div className="text-center text-gray-300 text-sm">or</div>

          <button
            type="button"
            className="flex items-center justify-center gap-2 bg-black/60 hover:bg-black/80 text-white py-2 rounded-lg border border-white/20 transition duration-300"
          >
            <span className="bg-white text-black px-2 py-1 rounded">NIN</span> Continue with NIN?
          </button>
        </form>
      </motion.div>
    </div>
  );
}
