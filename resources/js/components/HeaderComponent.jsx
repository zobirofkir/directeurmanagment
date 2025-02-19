import { Link } from '@inertiajs/react';
import { motion } from 'framer-motion';

const HeaderComponent = () => {
  return (
    <motion.header
      className="bg-white shadow-lg w-full fixed top-0 z-50"
      initial={{ y: -50, opacity: 0 }}
      animate={{ y: 0, opacity: 1 }}
      transition={{ duration: 0.5 }}
    >
      <div className="container mx-auto px-6 py-4 flex justify-between items-center">
        {/* Logo */}
        <motion.div
          className="text-2xl font-extrabold text-gray-900 drop-shadow-lg"
          whileHover={{ scale: 1.1 }}
        >
          <Link href="/" className="hover:text-gray-600 transition-colors">
            Directeur System
          </Link>
        </motion.div>

        {/* Auth Button */}
        <motion.div
          className="lg:flex lg:items-center"
          whileHover={{ scale: 1.1 }}
        >
          <Link
            href='/login'
            className="text-md font-semibold text-white bg-gradient-to-r from-gray-200 to-black px-5 py-2 rounded-full shadow-lg transform hover:scale-10 transition-all"
          >
            Login
          </Link>
        </motion.div>
      </div>
    </motion.header>
  );
};

export default HeaderComponent;
