import React, { useEffect, useRef } from "react";
import Message from "./Message";
import { motion } from "framer-motion";
import MediaMessage from "./MediaMessage";

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
                backgroundImage:
                    "radial-gradient(circle at center, #f3f4f6 10%, transparent 11%)",
                backgroundSize: "20px 20px",
            }}
        >
            <div className="max-w-4xl mx-auto space-y-4">
                {messages.map((message) => (
                    <div
                        key={message.id}
                        className={`flex ${
                            message.isSender ? "justify-end" : "justify-start"
                        }`}
                    >
                        <div
                            className={`max-w-[70%] rounded-lg p-3 ${
                                message.isSender
                                    ? "bg-blue-500 text-white"
                                    : "bg-gray-200"
                            }`}
                        >
                            {message.media_url && (
                                <MediaMessage
                                    mediaUrl={message.media_url}
                                    mediaType={message.media_type}
                                />
                            )}
                            {message.content && (
                                <p className="break-words">{message.content}</p>
                            )}
                            <span
                                className={`text-xs ${
                                    message.isSender
                                        ? "text-blue-100"
                                        : "text-gray-500"
                                } block mt-1`}
                            >
                                {message.time}
                            </span>
                        </div>
                    </div>
                ))}
                <div ref={messagesEndRef} />
            </div>
        </motion.div>
    );
};

export default MessageList;
