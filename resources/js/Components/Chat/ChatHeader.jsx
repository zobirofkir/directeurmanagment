import React from 'react';

const ChatHeader = ({ selectedContact }) => {
    return (
        <div className="p-4 bg-white border-b border-gray-200 flex items-center">
            {selectedContact && (
                <>
                    <img
                        src={selectedContact.avatar}
                        alt={selectedContact.name}
                        className="w-10 h-10 rounded-full"
                    />
                    <div className="ml-3">
                        <h3 className="font-semibold">{selectedContact.name}</h3>
                        <p className="text-xs text-green-500">
                            {selectedContact.online ? "Active now" : "Offline"}
                        </p>
                    </div>
                </>
            )}
        </div>
    );
};

export default ChatHeader;
