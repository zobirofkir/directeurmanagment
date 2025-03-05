import React from 'react';
import { motion } from 'framer-motion';

const Message = ({ message }) => {
    const messageVariants = {
        hidden: {
            opacity: 0,
            x: message.isSender ? 50 : -50,
            y: 20
        },
        visible: {
            opacity: 1,
            x: 0,
            y: 0,
            transition: {
                type: "spring",
                stiffness: 200,
                damping: 20
            }
        }
    };

    return (
        <motion.div
            initial="hidden"
            animate="visible"
            variants={messageVariants}
            className={`flex ${message.isSender ? "justify-end" : "justify-start"} mb-4`}
        >
            {!message.isSender && (
                <img
                    src={message.sender.avatar}
                    alt={message.sender.name}
                    className="w-8 h-8 rounded-full mr-2 self-end"
                />
            )}
            <div
                className={`max-w-[70%] rounded-2xl p-4 shadow-sm ${
                    message.isSender
                        ? "bg-gradient-to-r from-blue-500 to-blue-600 text-white"
                        : "bg-white border border-gray-100"
                }`}
            >
                {message.content && (
                    <p className="text-[15px] leading-relaxed">{message.content}</p>
                )}
                {message.media_url && (
                    <motion.div
                        initial={{ scale: 0.8, opacity: 0 }}
                        animate={{ scale: 1, opacity: 1 }}
                        transition={{ duration: 0.3 }}
                        className="mt-2"
                    >
                        {message.media_type === 'image' && (
                            <img
                                src={message.media_url}
                                alt="Shared image"
                                className="max-w-full rounded-lg cursor-pointer hover:opacity-90 transition-opacity"
                                onClick={() => window.open(message.media_url, '_blank')}
                            />
                        )}
                        {message.media_type === 'video' && (
                            <video
                                controls
                                className="max-w-full rounded-lg"
                            >
                                <source src={message.media_url} type="video/mp4" />
                                Your browser does not support the video tag.
                            </video>
                        )}
                    </motion.div>
                )}
                <p
                    className={`text-xs mt-2 ${
                        message.isSender ? "text-blue-100" : "text-gray-400"
                    }`}
                >
                    {message.time}
                </p>
            </div>
        </motion.div>
    );
};

export default Message;
