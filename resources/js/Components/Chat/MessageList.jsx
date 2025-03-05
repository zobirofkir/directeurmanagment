import React from 'react';
import Message from './Message';

const MessageList = ({ messages }) => {
    return (
        <div className="flex-1 overflow-y-auto p-4 bg-gray-50">
            <div className="space-y-4">
                {messages.map((message) => (
                    <Message key={message.id} message={message} />
                ))}
            </div>
        </div>
    );
};

export default MessageList;
