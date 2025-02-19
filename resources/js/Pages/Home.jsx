import Layout from '@/Layouts/Layout';
import { Head } from '@inertiajs/react';
import React from 'react';
import { motion } from 'framer-motion';
import HeroComponent from '@/components/HeroComponent';
import FeatureComponent from '@/components/FeatureComponent';
import CallComponent from '@/components/CallComponent';

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

      {/* Hero Section */}
      <HeroComponent containerVariants={containerVariants} itemVariants={itemVariants} hoverVariants={hoverVariants}/>

      {/* Features Section */}
      <FeatureComponent containerVariants={containerVariants} itemVariants={itemVariants} hoverVariants={hoverVariants}/>

      {/* Call-to-Action Section */}
      <CallComponent containerVariants={containerVariants} itemVariants={itemVariants} hoverVariants={hoverVariants}/>

    </Layout>
  );
};

export default Home;
