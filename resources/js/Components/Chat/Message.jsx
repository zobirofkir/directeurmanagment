import React from 'react';

const Message = ({ message }) => {
    return (
        <div className={`flex ${message.isSender ? "justify-end" : "justify-start"}`}>
            <div
                className={`max-w-[70%] rounded-lg p-3 ${
                    message.isSender
                        ? "bg-blue-500 text-white"
                        : "bg-white border border-gray-200"
                }`}
            >
                <p>{message.content}</p>
                <p
                    className={`text-xs mt-1 ${
                        message.isSender ? "text-blue-100" : "text-gray-500"
                    }`}
                >
                    {message.time}
                </p>
            </div>
        </div>
    );
};

export default Message;
