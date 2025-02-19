import React from 'react'
import { motion } from 'framer-motion'

const HeroComponent = () => {
  return (
    <section className="bg-gray-900 text-white py-40 mt-16">
        <div className="container mx-auto px-4 text-center">
        <motion.h1
            className="text-5xl font-bold mb-4"
            initial={{ opacity: 0, y: -20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.8 }}
        >
            Welcome to Directeur System
        </motion.h1>
        <motion.p
            className="text-xl mb-8"
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.8, delay: 0.3 }}
        >
            Streamline your management processes with our powerful and intuitive tools.
        </motion.p>
        <motion.div
            className="space-x-4"
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            transition={{ delay: 0.6 }}
        >
            <motion.a
            href="/signup"
            className="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors"
            whileHover={{ scale: 1.1 }}
            whileTap={{ scale: 0.9 }}
            >
            Get Started
            </motion.a>
            <motion.a
            href="/features"
            className="bg-transparent border border-white text-white px-6 py-3 rounded-lg hover:bg-white hover:text-gray-900 transition-colors"
            whileHover={{ scale: 1.1 }}
            whileTap={{ scale: 0.9 }}
            >
            Learn More
            </motion.a>
        </motion.div>
        </div>
    </section>
  )
}

export default HeroComponent
