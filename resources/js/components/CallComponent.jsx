import { motion } from 'framer-motion'

const CallComponent = ({containerVariants, itemVariants, hoverVariants}) => {
  return (
    <section className="bg-gray-100 py-20">
        <div className="container mx-auto px-4 text-center">
        <motion.h2
            className="text-3xl font-bold mb-4"
            initial={{ opacity: 0, y: -20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.8 }}
        >
            Contact Information
        </motion.h2>
        <div className="mt-10 flex flex-col items-center gap-4">
          <motion.img
            src="https://media.istockphoto.com/id/1338737959/photo/little-kids-schoolchildren-pupils-students-running-hurrying-to-the-school-building-for.jpg?s=612x612&w=0&k=20&c=u2eZV7PY4TTGKvxRBRkhiaDoFFEFPKeOlCsYARCqFSI="
            alt="Contact Us"
            className="w-32 h-32 rounded-full shadow-lg"
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            transition={{ duration: 0.8, delay: 0.3 }}
          />
          <motion.p
            className="text-lg text-gray-700"
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            transition={{ duration: 0.8, delay: 0.5 }}
          >
            Email: <a href="mailto:contact@example.com" className="text-blue-600 underline">contact@example.com</a>
          </motion.p>
          <motion.p
            className="text-lg text-gray-700"
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            transition={{ duration: 0.8, delay: 0.6 }}
          >
            Phone: <a href="tel:+1234567890" className="text-blue-600 underline">+1 234 567 890</a>
          </motion.p>
          <motion.p
            className="text-lg text-gray-700"
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            transition={{ duration: 0.8, delay: 0.7 }}
          >
            Address: 123 Business Street, City, Country
          </motion.p>
        </div>
        </div>
    </section>
  )
}

export default CallComponent
