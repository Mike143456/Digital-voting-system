"use client";
import { useForm } from "react-hook-form";
import { useState } from "react";
import CustomInput from "../utils/customInput";
import { LoginData } from "./types/formTypes";
import { useRouter } from "next/navigation";
import AlertModal from "../utils/alertModal";
import Loader from "../utils/loader";

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
    <div className="min-h-screen flex items-center justify-center bg-[url('/images/nigeriaFlag.jpg')] bg-cover bg-center px-4 sm:px-6 lg:px-8">
      <form
        onSubmit={handleSubmit(onSubmit)}
        className="bg-white p-6 sm:p-8 lg:p-10 rounded-2xl shadow-lg w-full max-w-sm sm:max-w-md lg:max-w-lg">
        <h2 className="text-xl sm:text-2xl lg:text-3xl font-bold mb-6 text-center text-green-300">
          VOTER&apos;S LOGIN
        </h2>

        <CustomInput
          label="VOTER IDENTIFICATION NUMBER (VIN)"
          error={errors.voterId}
          {...register("voterId", {
            required: "VIN is required",
            minLength: { value: 20, message: "VIN is 20 alphabets and numbers" },
            maxLength: { value: 20, message: "VIN is 20 alphabets and numbers" }
          })}
        />
        <CustomInput
          label="NATIONAL IDENTIFICATION NUMBER (NIN)"
          type="number"
          error={errors.nin}
          {...register("nin", {
            required: "NIN is required",
            minLength: { value: 11, message: "NIN is 11 numbers" },
            maxLength: { value: 11, message: "NIN is 11 numbers" }
          })}
        />

        <button
          type="submit"
          className=" w-full bg-green-600 text-white py-2 sm:py-3 rounded-lg hover:bg-green-600 transition cursor-pointer text-sm sm:text-base lg:text-lg"
          disabled={loading}
        >
          {loading ? <Loader /> : "LOGIN"}
        </button>
      </form>

      <AlertModal
        isOpen={successModal}
        title="Login Successful"
        subtitle={`Vote Wisely!`}
        type="success"
        onClose={() => setSuccessModal(false)}
      />

      <AlertModal
        isOpen={errorModal}
        title="Error Signup"
        subtitle={signupError}
        type="error"
        onClose={() => setErrorModal(false)}
      />
    </div>
  );
}
