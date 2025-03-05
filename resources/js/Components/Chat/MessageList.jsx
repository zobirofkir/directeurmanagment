import React from 'react';
import Message from './Message';

const MessageList = ({ messages }) => {
    return (
        <div className="flex-1 overflow-y-auto p-4">
            {messages.map((message) => (
                <div
                    key={message.id}
                    className={`flex ${
                        message.isSender ? "justify-end" : "justify-start"
                    } mb-4`}
                >
                    <div className={`max-w-[70%] ${message.isSender ? "bg-blue-500 text-white" : "bg-gray-200"} rounded-lg p-3`}>
                        {message.content && (
                            <p className="mb-2">{message.content}</p>
                        )}
                        {message.media_url && message.media_type === 'image' && (
                            <img
                                src={message.media_url}
                                alt="Shared image"
                                className="max-w-full rounded-lg"
                            />
                        )}
                        {message.media_url && message.media_type === 'video' && (
                            <video
                                controls
                                className="max-w-full rounded-lg"
                            >
                                <source src={message.media_url} type="video/mp4" />
                                Your browser does not support the video tag.
                            </video>
                        )}
                        <span className="text-xs opacity-75 block text-right">
                            {message.time}
                        </span>
                    </div>
                </div>
            ))}
        </div>
    );
};

export default MessageList;
