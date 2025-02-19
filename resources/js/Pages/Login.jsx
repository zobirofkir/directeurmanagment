import { useState, useEffect } from "react";
import { motion } from "framer-motion";
import { ToastContainer, toast } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";

const Login = ({ error }) => {
  const [login, setLogin] = useState("");
  const [password, setPassword] = useState("");
  const [csrfToken, setCsrfToken] = useState("");

  useEffect(() => {
    const token = document.querySelector('meta[name="csrf-token"]')?.content;
    setCsrfToken(token || "");
  }, []);

  const handleLogin = async (e) => {
    e.preventDefault();
    if (!csrfToken) {
      toast.error("CSRF token is missing. Please refresh the page and try again.");
      return;
    }

    try {
      const response = await fetch('/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({ login, password }),
      });

      if (!response.ok) {
        throw new Error('Network response was not ok');
      }

      const data = await response.json();
      if (data.success) {
        window.location.replace('/admin');
      } else {
        toast.error(data.error || "Login failed");
      }
    } catch (error) {
      toast.error("An error occurred. Please try again.");
    }
  };

  useEffect(() => {
    if (error) toast.error(error);
  }, [error]);

  return (
    <div className="flex justify-center items-center h-screen bg-gray-100">
      <ToastContainer />
      <motion.div
        initial={{ scale: 0.8, opacity: 0 }}
        animate={{ scale: 1, opacity: 1 }}
        className="bg-white p-8 rounded-2xl shadow-lg w-[500px] h-[400px] flex flex-col items-center"
      >
        <h2 className="text-gray-800 text-3xl font-bold mb-6">Login</h2>
        <form onSubmit={handleLogin} className="space-y-6">
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
    </div>
  );
};

export default Login;
