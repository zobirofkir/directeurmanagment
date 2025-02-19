import Layout from '@/Layouts/Layout';
import { Head } from '@inertiajs/react';
import React from 'react';
import { motion } from 'framer-motion';
import HeroComponent from '@/components/HeroComponent';

const containerVariants = {
  hidden: { opacity: 0 },
  visible: { opacity: 1, transition: { staggerChildren: 0.3 } },
};

const itemVariants = {
  hidden: { opacity: 0, y: 20 },
  visible: { opacity: 1, y: 0 },
};

const hoverVariants = {
  hover: { scale: 1.05, rotateY: 10, rotateX: 10, transition: { duration: 0.3 } },
};

const Home = () => {
  return (
    <Layout>
      <Head title="Home" />
      <HeroComponent />
      {/* Features Section */}
      <section className="py-20">
        <div className="container mx-auto px-4">
          <motion.h2
            className="text-3xl font-bold text-center mb-12"
            initial={{ opacity: 0, y: -20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.8 }}
          >
            Features
          </motion.h2>
          <motion.div
            className="grid grid-cols-1 md:grid-cols-3 gap-8"
            variants={containerVariants}
            initial="hidden"
            whileInView="visible"
            viewport={{ once: true }}
          >
            {/* Feature 1 */}
            <motion.div
              className="bg-white p-6 rounded-lg shadow-lg text-center"
              variants={itemVariants}
              whileHover="hover"
              variants={hoverVariants}
            >
              <div className="text-4xl mb-4">📊</div>
              <h3 className="text-xl font-bold mb-2">Advanced Analytics</h3>
              <p className="text-gray-700">
                Gain insights with real-time data and customizable reports.
              </p>
            </motion.div>

            {/* Feature 2 */}
            <motion.div
              className="bg-white p-6 rounded-lg shadow-lg text-center"
              variants={itemVariants}
              whileHover="hover"
              variants={hoverVariants}
            >
              <div className="text-4xl mb-4">🤝</div>
              <h3 className="text-xl font-bold mb-2">Team Collaboration</h3>
              <p className="text-gray-700">
                Work seamlessly with your team using integrated tools.
              </p>
            </motion.div>

            {/* Feature 3 */}
            <motion.div
              className="bg-white p-6 rounded-lg shadow-lg text-center"
              variants={itemVariants}
              whileHover="hover"
              variants={hoverVariants}
            >
              <div className="text-4xl mb-4">🔒</div>
              <h3 className="text-xl font-bold mb-2">Secure & Reliable</h3>
              <p className="text-gray-700">
                Your data is safe with our state-of-the-art security measures.
              </p>
            </motion.div>
          </motion.div>
        </div>
      </section>

      {/* Call-to-Action Section */}
      <section className="bg-gray-100 py-20">
        <div className="container mx-auto px-4 text-center">
          <motion.h2
            className="text-3xl font-bold mb-4"
            initial={{ opacity: 0, y: -20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.8 }}
          >
            Ready to Get Started?
          </motion.h2>
          <motion.p
            className="text-xl mb-8"
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.8, delay: 0.3 }}
          >
            Join thousands of users who are already transforming their management processes.
          </motion.p>
          <motion.a
            href="/signup"
            className="bg-blue-600 text-white px-8 py-4 rounded-lg hover:bg-blue-700 transition-colors"
            whileHover={{ scale: 1.1 }}
            whileTap={{ scale: 0.9 }}
          >
            Sign Up Now
          </motion.a>
        </div>
      </section>
    </Layout>
  );
};

export default Home;
