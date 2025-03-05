import React, { useEffect, useRef } from 'react';
import Message from './Message';
import { motion } from 'framer-motion';

const MessageList = ({ messages }) => {
    const messagesEndRef = useRef(null);

    const scrollToBottom = () => {
        messagesEndRef.current?.scrollIntoView({ behavior: "smooth" });
    };

    useEffect(() => {
        scrollToBottom();
    }, [messages]);

    return (
        <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            className="flex-1 overflow-y-auto p-4 bg-gray-50"
            style={{
                backgroundImage: 'radial-gradient(circle at center, #f3f4f6 10%, transparent 11%)',
                backgroundSize: '20px 20px'
            }}
        >
            <div className="max-w-4xl mx-auto space-y-4">
                {messages.map((message) => (
                    <Message key={message.id} message={message} />
                ))}
                <div ref={messagesEndRef} />
            </div>
        </motion.div>
    );
};

export default MessageList;
