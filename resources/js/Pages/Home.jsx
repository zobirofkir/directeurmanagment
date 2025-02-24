import { useState, useEffect } from "react";
import { motion } from "framer-motion";
import { ToastContainer, toast } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";
import { getCsrfToken, handleLoginRequest } from "../Utils/authUtils";
import Layout from "@/Layouts/Layout";
import Logo from '../assets/images/logo.png';
import { Head } from "@inertiajs/react";

const Home = ({ error }) => {
  const [login, setLogin] = useState("");
  const [password, setPassword] = useState("");
  const [csrfToken, setCsrfToken] = useState("");

  useEffect(() => {
    setCsrfToken(getCsrfToken());
  }, []);

  const handleLogin = async (e) => {
    e.preventDefault();
    await handleLoginRequest(login, password, csrfToken);
  };

  useEffect(() => {
    if (error) toast.error(error);
  }, [error]);

  return (
    <Layout>
        <Head title="login"/>
      <div className="flex flex-col md:flex-row justify-center items-center h-screen bg-gray-100 p-4">
        <ToastContainer />
        <motion.div
          initial={{ scale: 0.8, opacity: 0 }}
          animate={{ scale: 1, opacity: 1 }}
          className="bg-white p-8 rounded-2xl shadow-lg w-full md:w-1/2 lg:w-1/3 flex flex-col items-center mb-8 md:mb-0 md:mr-8"
        >
          <h2 className="text-gray-800 text-3xl font-bold mb-6">Login</h2>
          <form onSubmit={handleLogin} className="space-y-6 w-full">
            <motion.input
              type="text"
              placeholder="Email or Username"
              value={login}
              onChange={(e) => setLogin(e.target.value)}
              className="w-full px-4 py-3 bg-gray-200 text-gray-800 rounded-lg"
            />
            <motion.input
              type="password"
              placeholder="Password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              className="w-full px-4 py-3 bg-gray-200 text-gray-800 rounded-lg"
            />
            <motion.button
              className="w-full bg-gray-800 text-white py-2 rounded-lg"
              type="submit"
            >
              Login
            </motion.button>
          </form>
        </motion.div>
        <motion.div
          initial={{ scale: 0.8, opacity: 0 }}
          animate={{ scale: 1, opacity: 1 }}
          className="w-full md:w-1/2 lg:w-1/3 flex justify-center md:block hidden"
        >
          <img
            src={Logo}
            alt="Login Visual"
            className="rounded-md max-w-full h-auto"
          />
        </motion.div>
      </div>
    </Layout>
  );
};

export default Home;
