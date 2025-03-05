import React from 'react';
import { motion } from 'framer-motion';

const MessageInput = ({ newMessage, setNewMessage, onSubmit }) => {
    return (
        <form onSubmit={onSubmit} className="w-full">
            <div className="flex items-center space-x-2">
                <input
                    type="text"
                    value={newMessage}
                    onChange={(e) => setNewMessage(e.target.value)}
                    placeholder="Type a message..."
                    className="w-full px-6 py-3 bg-gray-50 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all duration-200"
                />
                <motion.button
                    whileHover={{ scale: 1.1 }}
                    whileTap={{ scale: 0.9 }}
                    type="submit"
                    className="p-3 bg-blue-500 hover:bg-blue-600 rounded-full transition-colors duration-200 flex-shrink-0"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        className="h-6 w-6 text-white transform rotate-45"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            strokeLinecap="round"
                            strokeLinejoin="round"
                            strokeWidth={2}
                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"
                        />
                    </svg>
                </motion.button>
            </div>
        </form>
    );
};

export default MessageInput;
