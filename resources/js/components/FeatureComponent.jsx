import React from 'react'
import { motion } from 'framer-motion'

const FeatureComponent = ({containerVariants, itemVariants, hoverVariants}) => {
  return (
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

  )
}

export default FeatureComponent
